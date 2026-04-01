<?php

namespace App\Controller;

use App\Services\KordisClient;
use App\Services\MyGES;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AgendaController extends AbstractController
{
    #[Route('/agenda', name: 'app_agenda')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Get the current week
        $currentWeek = date('W');

        // Get the days of the week without the sunday
        $days = [];
        for ($i = 1; $i <= 6; $i++) {
            $days[] = date('Y-m-d', strtotime('2025W' . $currentWeek . $i));
        }

        // Transform the first day and the last day into timestamps in ms
        $startAt = strtotime($days[0]) * 1000;
        $endAt = strtotime($days[5]) * 1000;

        // Request the API to get the events of the week
        $student = $this->getUser()->getStudent($entityManager);
        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGES = new MyGES(new KordisClient($credentials[0], $credentials[1]));

        $events = $myGES->getAgenda($startAt, $endAt);

        // Filter the events day by day
        $filteredEvents = [];
        foreach ($events as $event) {
            $date = date('Y-m-d', $event->start_date / 1000);
            $filteredEvents[$date][] = $event;
        }

        $events = $filteredEvents;

        // Sort the events by start date
        foreach ($events as $date => $eventsOfDay) {
            usort($eventsOfDay, function ($a, $b) {
                return $a->start_date - $b->start_date;
            });
            $events[$date] = $eventsOfDay;
        }

        // Sort the array by date
        ksort($events);

        return $this->render('agenda/index.html.twig', [
            'days' => $days,
            'events' => $events
        ]);
    }

    #[Route('/agenda/{week}', name: 'app_agenda_week')]
    public function week(EntityManagerInterface $entityManager, string $week): Response
    {
        // Get the days of the week without the sunday
        $days = [];
        for ($i = 1; $i <= 6; $i++) {
            $days[] = date('Y-m-d', strtotime('2025W' . $week . $i));
        }

        // Transform the first day and the last day into timestamps in ms
        $startAt = strtotime($days[0]) * 1000;
        $endAt = strtotime($days[5]) * 1000;

        // Request the API to get the events of the week
        $student = $this->getUser()->getStudent($entityManager);
        $credentials = explode(':', MyGES::decodeCredentials($student->getMyGesCredentialsToken()));
        $myGES = new MyGES(new KordisClient($credentials[0], $credentials[1]));

        $events = $myGES->getAgenda($startAt, $endAt);

        // Filter the events day by day
        $filteredEvents = [];
        foreach ($events as $event) {
            $date = date('Y-m-d', $event->start_date / 1000);
            $filteredEvents[$date][] = $event;
        }

        $events = $filteredEvents;

        // Sort the events by start date
        foreach ($events as $date => $eventsOfDay) {
            usort($eventsOfDay, function ($a, $b) {
                return $a->start_date - $b->start_date;
            });
            $events[$date] = $eventsOfDay;
        }

        // Sort the array by date
        ksort($events);

        return $this->render('agenda/index.html.twig', [
            'days' => $days,
            'events' => $events,
            'week' => $week
        ]);
    }
}
