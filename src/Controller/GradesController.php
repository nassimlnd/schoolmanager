<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Grade;
use App\Entity\Teacher;
use App\Repository\CourseRepository;
use App\Repository\GradeRepository;
use App\Repository\TeacherRepository;
use App\Services\KordisClient;
use App\Services\MyGES;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/student')]
class GradesController extends AbstractController
{
    #[Route('/grades', name: 'app_grades')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $courses = $student->getCourses();

        return $this->render('grades/index.html.twig', [
            'courses' => $courses,
        ]);
    }

    #[Route('/grades/sync', name: 'app_grades_sync')]
    public function syncGradesWithMyGES(EntityManagerInterface $entityManager, TeacherRepository $teacherRepository, GradeRepository $gradeRepository)
    {
        $student = $this->getUser()->getStudent($entityManager);

        if ($student->getMyGesCredentialsToken() == null) {
            return $this->redirectToRoute('app_onboarding_myges');
        }

        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGesClient = new MyGES(new KordisClient($credentials[0], $credentials[1]));
        $grades = $myGesClient->getGrades(date('Y') - 1);

        foreach ($grades as $grade) {
            $course = $entityManager->getRepository(Course::class)->findOneBy(['rcId' => $grade->rc_id]);
            if (!$course) {
                $course = new Course();
                $course->setRcId($grade->rc_id);
                $course->setName($grade->course);
                $course->setCoefficient($grade->coef);
                $course->setEcts($grade->ects);
                $course->addStudent($student);
                $entityManager->persist($course);
            }

            if (in_array($student, array($course->getStudents()))) {
                $course->addStudent($student);
                $entityManager->persist($course);
            }

            $teacher = $teacherRepository->getByName($grade->teacher_first_name, $grade->teacher_last_name);

            if (!$teacher) {
                $rawTeacher = $myGesClient->getTeacherByName($grade->teacher_first_name, $grade->teacher_last_name);
                $teacher = new Teacher();
                $teacher->setFirstName($rawTeacher->firstname);
                $teacher->setLastName($rawTeacher->lastname);
                $teacher->setTeacherId($rawTeacher->uid);

                $entityManager->persist($teacher);
            }

            if ($course->getTeacher() != $teacher) {
                $course->setTeacher($teacher);
                $entityManager->persist($course);
            }

            $studentGrades = $gradeRepository->getGradesByStudent($student->getId());;

            foreach ($grade->grades as $gradeValue) {
                $studentGrade = array_filter($studentGrades, function($studentGrade) use ($grade, $gradeValue) {
                    return $studentGrade->getCourse()->getRcId() == $grade->rc_id && $studentGrade->getGrade() == $gradeValue;
                });

                if (!$studentGrade) {
                    $studentGrade = new Grade();
                    $studentGrade->setGrade($gradeValue);
                    $studentGrade->setLetterMark($grade->letter_mark);
                    $studentGrade->setTrimester($grade->trimester);
                    $studentGrade->setTrimesterName($grade->trimester_name);
                    $studentGrade->setYear($grade->year);
                    $studentGrade->setAbsences($grade->absences);
                    $studentGrade->setLates($grade->lates);
                    $studentGrade->setCourse($course);
                    $studentGrade->setStudent($student);
                    $entityManager->persist($studentGrade);
                }
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_grades');
    }

    #[Route('/grades/{rc_id}', name: 'app_grades_course')]
    public function gradesByCourse(int $rc_id, EntityManagerInterface $entityManager, CourseRepository $courseRepository, GradeRepository $gradeRepository): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $courses = $student->getCourses();
        $course = $courseRepository->findOneBy(['rcId' => $rc_id]);
        $grades = $gradeRepository->getGradesByStudentAndCourse($course->getId(), $student->getId());

        return $this->render('grades/course.html.twig', [
            'courses' => $courses,
            'course' => $course,
            'grades' => $grades,
        ]);
    }
}
