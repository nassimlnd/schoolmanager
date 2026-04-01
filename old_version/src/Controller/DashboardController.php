<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (in_array('ROLE_STUDENT', $this->getUser()->getRoles())) {
            if ($this->getUser()->getStudent($entityManager) === null) {
                return $this->redirectToRoute('app_onboarding');
            }
        }

        return $this->render('dashboard/index.html.twig');
    }
}
