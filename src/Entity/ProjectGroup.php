<?php

namespace App\Entity;

use App\Repository\ProjectGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectGroupRepository::class)]
class ProjectGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $groupId = null;

    #[ORM\ManyToOne(inversedBy: 'projectGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Project $project = null;

    /**
     * @var Collection<int, Student>
     */
    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'projectGroups')]
    private Collection $students;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ProjectLog>
     */
    #[ORM\OneToMany(targetEntity: ProjectLog::class, mappedBy: 'projectGroup', orphanRemoval: true)]
    private Collection $projectLogs;

    /**
     * @var Collection<int, ProjectGroupFile>
     */
    #[ORM\OneToMany(targetEntity: ProjectGroupFile::class, mappedBy: 'projectGroup', orphanRemoval: true)]
    private Collection $projectGroupFiles;

    public function __construct()
    {
        $this->students = new ArrayCollection();
        $this->projectLogs = new ArrayCollection();
        $this->projectGroupFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getGroupId(): ?int
    {
        return $this->groupId;
    }

    public function setGroupId(int $groupId): static
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        $this->students->removeElement($student);

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, ProjectLog>
     */
    public function getProjectLogs(): Collection
    {
        return $this->projectLogs;
    }

    public function addProjectLog(ProjectLog $projectLog): static
    {
        if (!$this->projectLogs->contains($projectLog)) {
            $this->projectLogs->add($projectLog);
            $projectLog->setProjectGroup($this);
        }

        return $this;
    }

    public function removeProjectLog(ProjectLog $projectLog): static
    {
        if ($this->projectLogs->removeElement($projectLog)) {
            // set the owning side to null (unless already changed)
            if ($projectLog->getProjectGroup() === $this) {
                $projectLog->setProjectGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProjectGroupFile>
     */
    public function getProjectGroupFiles(): Collection
    {
        return $this->projectGroupFiles;
    }

    public function addProjectGroupFile(ProjectGroupFile $projectGroupFile): static
    {
        if (!$this->projectGroupFiles->contains($projectGroupFile)) {
            $this->projectGroupFiles->add($projectGroupFile);
            $projectGroupFile->setProjectGroup($this);
        }

        return $this;
    }

    public function removeProjectGroupFile(ProjectGroupFile $projectGroupFile): static
    {
        if ($this->projectGroupFiles->removeElement($projectGroupFile)) {
            // set the owning side to null (unless already changed)
            if ($projectGroupFile->getProjectGroup() === $this) {
                $projectGroupFile->setProjectGroup(null);
            }
        }

        return $this;
    }
}
