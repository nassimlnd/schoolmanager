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
use App\Utils\MarkdownUtils;
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

    #[Route('/projects/{id}/overview', name: 'app_projects_overview')]
    public function overview(Project $project): Response
    {
        $project->setDescription(MarkdownUtils::markdownToHtml($project->getDescription()));
        return $this->render('projects/overview.html.twig', [
            'project' => $project
        ]);
    }

    #[Route('/projects/{id}/groups', name: 'app_projects_groups')]
    public function groups(Project $project, EntityManagerInterface $entityManager)
    {
        $groups = $project->getProjectGroups();

        return $this->render('projects/groups.html.twig', [
            'groups' => $groups,
            'project' => $project
        ]);
    }

    #[Route('/projects/{id}/files', name: 'app_projects_files')]
    public function files(Project $project): Response
    {
        return $this->render('projects/files.html.twig', [
            'project' => $project,
            'files' => null
        ]);
    }
}
