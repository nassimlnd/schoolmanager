<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Project;
use App\Entity\ProjectGroup;
use App\Entity\ProjectLog;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\ProjectGroupRepository;
use App\Repository\ProjectRepository;
use App\Repository\TeacherRepository;
use App\Services\KordisClient;
use App\Services\MyGES;
use App\Utils\DateTimeUtils;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student')]
class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $projects = $student->getProjects();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects
        ]);
    }

    #[Route('/projects/sync', name: 'app_projects_sync')]
    public function sync(EntityManagerInterface $entityManager, CourseRepository $courseRepository, TeacherRepository $teacherRepository, ProjectRepository $projectRepository, ProjectGroupRepository $groupRepository): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        if ($student->getMyGesCredentialsToken() == null) {
            return $this->redirectToRoute('app_onboarding_myges');
        }

        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGesClient = new MyGES(new KordisClient($credentials[0], $credentials[1]));

        $projects = $myGesClient->getProjects(date('Y') - 1);

        foreach ($projects as $rawProject) {
            if ($projectRepository->getByProjectId($rawProject->project_id)) {
                $project = $projectRepository->getByProjectId($rawProject->project_id);
                $project->addStudent($student);
                $entityManager->persist($project);
                continue;
            }

            $project = new Project();
            $project->setName($rawProject->name);
            $project->setProjectId($rawProject->project_id);
            $project->setDescription($rawProject->project_detail_plan);
            $project->setDraft($rawProject->is_draft);
            $project->setYear($rawProject->year);
            $project->setUpdateDate((new \DateTime())->setTimestamp(DateTimeUtils::timestampMsToSec($rawProject->update_date)));
            $project->setUpdateUser($rawProject->update_user);
            $project->addStudent($student);

            if (!$courseRepository->getByRcId($rawProject->rc_id)) {
                continue;
            } else {
                $course = $courseRepository->getByRcId($rawProject->rc_id);
                $project->setCourse($course);
            }

            foreach ($rawProject->groups as $rawGroup)
            {
                if ($groupRepository->getByGroupId($rawGroup->project_group_id)) {
                    $group = $groupRepository->getByGroupId($rawGroup->project_group_id);
                    $group->addStudent($student);
                    $project->addProjectGroup($group);
                    continue;
                }

                $isGoodGroup = false;
                foreach ($rawGroup->project_group_students as $groupStudent) {
                    if ($groupStudent->firstname == $student->getFirstName() && $groupStudent->lastname == $student->getLastName()) {
                        $isGoodGroup = true;
                        break;
                    }
                }

                if ($isGoodGroup) {
                    $group = new ProjectGroup();
                    $group->setProject($project);
                    $group->addStudent($student);
                    $group->setName($rawGroup->group_name);
                    $group->setGroupId($rawGroup->project_group_id);

                    $project->addProjectGroup($group);
                    break;
                }
            }

            foreach ($rawProject->project_group_logs as $rawGroupLog)
            {
                $projectLog = new ProjectLog();
                $projectLog->setProject($project);
                $projectLog->setCreatedAt((new DateTimeImmutable())->setTimestamp(DateTimeUtils::timestampMsToSec($rawGroupLog->pgl_date)));
                $projectLog->setDescription($rawGroupLog->pgl_describe);
                $projectLog->setProjectGroup($groupRepository->getByGroupId($rawGroupLog->project_group_id));
                $projectLog->setActionType($rawGroupLog->pgl_type_action);
                $projectLog->setStudent($student);
            }

            if ($teacherRepository->getByTeacherId($rawProject->teacher_id)) {
                $teacher = $teacherRepository->getByTeacherId($rawProject->teacher_id);
            } else {
                $rawTeacher = $myGesClient->getTeacher($rawProject->teacher_id);
                $teacher = new Teacher();
                $teacher->setTeacherId($rawTeacher->uid);
                $teacher->setFirstName($rawTeacher->first_name);
                $teacher->setLastName($rawTeacher->last_name);
            }

            $project->setTeacher($teacher);
            $project->setCreatedAt((new \DateTimeImmutable())->setTimestamp(DateTimeUtils::timestampMsToSec($rawProject->project_create_date)));
            $entityManager->persist($project);
        }

        $entityManager->flush();
        return $this->redirectToRoute('app_projects');
    }

    #[Route('/projects/{id}/overview', name: 'app_projects_overview')]
    public function overview(Project $project): Response
    {
        return $this->render('projects/overview.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/projects/{id}/groups', name: 'app_projects_groups')]
    public function groups(Project $project)
    {
        return $this->render('projects/groups.html.twig', [
            'project' => $project
        ]);
    }
}
