<?php

namespace App\Controller;

use App\Services\KordisClient;
use App\Services\MyGES;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GradesController extends AbstractController
{
    #[Route('/grades', name: 'app_grades')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGesClient = new MyGES(new KordisClient($credentials[0], $credentials[1]));
        $grades = $myGesClient->getGrades(date('Y') - 1);

        return $this->render('grades/index.html.twig', [
            'grades' => $grades,
        ]);
    }

    #[Route('/grades/{rc_id}', name: 'app_grades_course')]
    public function gradesByCourse(int $rc_id, EntityManagerInterface $entityManager): Response
    {
        $student = $this->getUser()->getStudent($entityManager);
        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGesClient = new MyGES(new KordisClient($credentials[0], $credentials[1]));
        $allGrades = $myGesClient->getGrades(date('Y') - 1);

        $grade = array_filter($allGrades, function ($grade) use ($rc_id) {
            return $grade->rc_id == $rc_id;
        });

        $grade = $grade[array_key_first($grade)];

        return $this->render('grades/course.html.twig', [
            'grades' => $allGrades,
            'course' => $grade,
        ]);
    }
}
