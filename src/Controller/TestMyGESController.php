<?php

namespace App\Controller;

use App\Services\KordisClient;
use App\Services\MyGES;
use DateInterval;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestMyGESController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        try {
            $kordisClient = new KordisClient('nlounadi', '569841NL*');
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()]);
        }

        $myGes = new MyGES($kordisClient);

        if ($kordisClient->getAccessToken() === null) {
            return $this->json(['error' => 'Access token is null']);
        }

//        $profile = $myGes->getCourses(2024);
        $profile = $myGes->getCourseDocuments(296923);

        dd($profile);

        return $this->json($profile);
    }
}
