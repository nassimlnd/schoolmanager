<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Project;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\ProjectRepository;
use App\Repository\TeacherRepository;
use App\Services\KordisClient;
use App\Services\MyGES;
use App\Utils\DateTimeUtils;
use Doctrine\ORM\EntityManagerInterface;
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
    public function sync(EntityManagerInterface $entityManager, CourseRepository $courseRepository, TeacherRepository $teacherRepository, ProjectRepository $projectRepository): Response
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

    #[Route('/projects/{id}', name: 'app_projects_show')]
    public function show(Project $project): Response
    {
        return $this->render('projects/show.html.twig', [
            'project' => $project
        ]);
    }
}
