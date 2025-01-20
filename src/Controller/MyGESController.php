<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Entity\Course;
use App\Entity\Project;
use App\Entity\ProjectGroup;
use App\Entity\ProjectLog;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Repository\ClasseRepository;
use App\Repository\CourseRepository;
use App\Repository\ProjectGroupRepository;
use App\Repository\ProjectLogRepository;
use App\Repository\ProjectRepository;
use App\Repository\StudentRepository;
use App\Repository\TeacherRepository;
use App\Services\KordisClient;
use App\Services\MyGES;
use App\Utils\DateTimeUtils;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MyGESController extends AbstractController
{
    #[Route('/myges/sync', name: 'app_myges_sync')]
    public function index(EntityManagerInterface $entityManager, CourseRepository $courseRepository, StudentRepository $studentRepository, TeacherRepository $teacherRepository, ClasseRepository $classeRepository, ProjectRepository $projectRepository, ProjectGroupRepository $groupRepository, ProjectLogRepository $projectLogRepository): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGES = new MyGES(new KordisClient($credentials[0], $credentials[1]));

        $this->syncTeachers($myGES, $teacherRepository, $entityManager);
        $this->syncCourses($myGES, $courseRepository, $entityManager, $teacherRepository);
        $this->syncStudents($myGES, $studentRepository, $entityManager, $classeRepository);
        $this->syncProjects($myGES, $projectRepository, $groupRepository, $projectLogRepository, $studentRepository, $courseRepository, $teacherRepository, $entityManager);

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @throws GuzzleException
     */
    public function syncCourses(MyGES $myGES, CourseRepository $courseRepository, EntityManagerInterface $entityManager, TeacherRepository $teacherRepository): void
    {
        $courses = $myGES->getCourses(date('Y') - 1);

        foreach ($courses as $cours) {
            if ($courseRepository->getByRcId($cours->rc_id)) {
                continue;
            }

            $course = new Course();

            $course->setCoefficient($cours->coef);
            $course->setEcts($cours->ects);
            $course->setName($cours->name);
            $course->setRcId($cours->rc_id);

            if ($teacherRepository->getByTeacherId($cours->teacher_id)) {
                $course->setTeacher($teacherRepository->getByTeacherId($cours->teacher_id));
            }

            $entityManager->persist($course);
        }

        $entityManager->flush();
    }

    /**
     * @throws GuzzleException
     */
    public function syncTeachers(MyGES $myGES, TeacherRepository $teacherRepository, EntityManagerInterface $entityManager): void
    {
        $teachers = $myGES->getTeachers(date('Y') - 1);

        foreach ($teachers as $rawTeacher) {
            if ($teacherRepository->getByTeacherId($rawTeacher->uid)) {
                continue;
            }

            $teacher = new Teacher();

            $teacher->setFirstName($rawTeacher->firstname);
            $teacher->setLastName($rawTeacher->lastname);
            $teacher->setEmail($rawTeacher->email);
            $teacher->setTeacherId($rawTeacher->uid);

            $entityManager->persist($teacher);
        }

        $entityManager->flush();
    }

    public function syncStudents(MyGES $myGES, StudentRepository $studentRepository, EntityManagerInterface $entityManager, ClasseRepository $classeRepository): void
    {
        $classe = $myGES->getClasses(date('Y') - 1)[0];

        if ($classeRepository->getByPuid($classe->puid)) {
            $class = $classeRepository->getByPuid($classe->puid);
        } else {
            $class = new Classe();
            $class->setPuid($classe->puid);
            $class->setYear($classe->year);
            $class->setName($classe->name);
            $class->setTrimester($classe->trimester);
            $class->setSchool($classe->school);
            $class->setPromotion($classe->promotion);
            $class->setDescription($classe->description);

            $entityManager->persist($class);
        }

        $students = $myGES->getStudents($classe->puid);

        foreach ($students as $rawStudent) {
            if ($studentRepository->getByStudentId($rawStudent->uid)) {
                continue;
            }

            $student = new Student();

            $student->setFirstName($rawStudent->firstname);
            $student->setLastName($rawStudent->lastname);
            $student->setEmail($rawStudent->email);
            $student->setStudentId($rawStudent->uid);
            $student->setClasse($class);

            $entityManager->persist($student);
        }

        $entityManager->flush();
    }

    public function syncProjects(MyGES $myGES, ProjectRepository $projectRepository, ProjectGroupRepository $groupRepository, ProjectLogRepository $projectLogRepository, StudentRepository $studentRepository, CourseRepository $courseRepository, TeacherRepository $teacherRepository, EntityManagerInterface $entityManager): void
    {
        $projects = $myGES->getProjects(date('Y') - 1);

        foreach ($projects as $rawProject) {
            if (!$courseRepository->getByRcId($rawProject->rc_id)) {
                continue;
            }

            if (!$teacherRepository->getByTeacherId($rawProject->teacher_id)) {
                continue;
            }

            if ($projectRepository->getByProjectId($rawProject->project_id)) {
                continue;
            }

            $project = new Project();

            $project->setCourse($courseRepository->getByRcId($rawProject->rc_id));
            $project->setTeacher($teacherRepository->getByTeacherId($rawProject->teacher_id));

            $project->setProjectId($rawProject->project_id);
            $project->setName($rawProject->name);

            if ($rawProject->project_detail_plan === '') {
                $project->setDescription($rawProject->project_teaching_goals);
            } else {
                $project->setDescription($rawProject->project_detail_plan);
            }

            $project->setYear($rawProject->year);
            $project->setDraft($rawProject->is_draft);
            $project->setCreatedAt((new \DateTimeImmutable())->setTimestamp(DateTimeUtils::timestampMsToSec($rawProject->project_create_date)));
            $project->setUpdateUser($rawProject->update_user);
            $project->setUpdateDate((new \DateTime())->setTimestamp(DateTimeUtils::timestampMsToSec($rawProject->update_date)));

            foreach ($rawProject->groups as $group) {
                if ($groupRepository->getByGroupId($group->project_group_id)) {
                    continue;
                }

                $projectGroup = new ProjectGroup();

                $projectGroup->setProject($project);
                $projectGroup->setGroupId($group->project_group_id);
                $projectGroup->setName($group->group_name);

                if (!is_null($group->project_group_students)) {
                    foreach ($group->project_group_students as $project_group_student) {
                        if (!$studentRepository->getByStudentId($project_group_student->u_id)) {
                            continue;
                        }

                        $projectGroup->addStudent($studentRepository->getByStudentId($project_group_student->u_id));
                    }
                }

                $project->addProjectGroup($projectGroup);
                $entityManager->persist($projectGroup);
            }

            if (!is_null($rawProject->project_group_logs)) {
                foreach ($rawProject->project_group_logs as $project_group_log) {
                    if ($projectLogRepository->getByProjectLogId($project_group_log->pgl_id)) {
                        continue;
                    }

                    if (!$studentRepository->getByStudentId($project_group_log->user_id)) {
                        continue;
                    }

                    $projectLog = new ProjectLog();

                    $projectLog->setProjectLogId($project_group_log->pgl_id);
                    $projectLog->setProjectGroup($project->getProjectGroups()->filter(function (ProjectGroup $entry) use ($project_group_log) {
                        return $entry->getGroupId() === $project_group_log->pgr_id;
                    })->first());
                    $projectLog->setProject($project);
                    $projectLog->setCreatedAt((new \DateTimeImmutable())->setTimestamp(DateTimeUtils::timestampMsToSec($project_group_log->pgl_date)));
                    $projectLog->setActionType($project_group_log->pgl_type_action);
                    $projectLog->setDescription($project_group_log->pgl_describe);

                    $projectLog->setStudent($studentRepository->getByStudentId($project_group_log->user_id));

                    $entityManager->persist($projectLog);
                }
            }

            $entityManager->persist($project);
        }

        $entityManager->flush();
    }
}
