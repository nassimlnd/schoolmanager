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
