<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\MyGESLoginType;
use App\Services\KordisClient;
use App\Services\MyGES;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OnboardingController extends AbstractController
{
    #[Route('/onboarding', name: 'app_onboarding', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($user->getStudent($entityManager) || !in_array('ROLE_STUDENT', $user->getRoles())) {
            return $this->redirectToRoute('app_dashboard');
        }

        $currentStep = $request->getSession()->get('onboarding_step', 1);

        if ($currentStep == 2) {
            if ($request->getSession()->get('onboarding_myges')) {
                return $this->redirectToRoute('app_onboarding_myges');
            } else {
                return $this->redirectToRoute('app_dashboard');
            }
        }

        return $this->redirectToRoute('app_onboarding_step' . $currentStep);
    }

    #[Route('/onboarding/step1', name: 'app_onboarding_step1', methods: ['GET', 'POST'])]
    public function step1(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            if ($request->request->get('method') == 'myges') {
                $request->getSession()->set('onboarding_step', 2);
                $request->getSession()->set('onboarding_myges', true);
                return $this->redirectToRoute('app_onboarding_myges');
            } else {
                $request->getSession()->set('onboarding_step', 2);
                $request->getSession()->set('onboarding_myges', false);
                return $this->redirectToRoute('app_dashboard');
            }
        }

        return $this->render('onboarding/step1.html.twig', [
            'controller_name' => 'OnboardingController',
        ]);
    }

    #[Route('/onboarding/myges', name: 'app_onboarding_myges', methods: ['GET', 'POST'])]
    public function myGes(Request $request)
    {
        $form = $this->createForm(MyGESLoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();
            $password = $form->get('password')->getData();

            if (!$username || !$password) {
                return $this->render('onboarding/myges.html.twig', [
                    'error' => 'Please fill in all fields',
                ]);
            }


            try {
                $kordisClient = new KordisClient($username, $password);
                $myGesClient = new MyGES($kordisClient);

                if ($myGesClient->getProfile() === null) {
                    throw new \Exception('Invalid credentials');
                }
            } catch (GuzzleException $e) {
                return $this->render('onboarding/myges.html.twig', [
                    'error' => 'Invalid credentials',
                    'loginForm' => $form,
                ]);
            } catch (\Exception $e) {
                return $this->render('onboarding/myges.html.twig', [
                    'error' => 'An error occurred',
                    'loginForm' => $form,
                ]);
            }


            if ($myGesClient->getProfile() === null) {
                return $this->render('onboarding/myges.html.twig', [
                    'error' => 'Invalid credentials',
                    'loginForm' => $form,
                ]);
            }

            $profile = json_encode($myGesClient->getProfile());
            $request->getSession()->set('myges_profile', $profile);

            return $this->redirectToRoute('app_onboarding_profile_review');
        }

        return $this->render('onboarding/myges.html.twig', [
            'loginForm' => $form,
            'error' => null,
        ]);
    }

    #[Route('/onboarding/profile-review', name: 'app_onboarding_profile_review', methods: ['GET'])]
    public function myGesProfileReview(Request $request)
    {
        $profile = json_decode($request->getSession()->get('myges_profile'), true);

//        dd($profile);

        $student = new Student();
        $student->setFirstName($profile['firstname']);
        $student->setLastName($profile['name']);

        return $this->render('onboarding/profile_review.html.twig', [
            'student' => $student,
        ]);

    }
}
