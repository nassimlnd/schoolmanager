<?php

namespace App\Entity;

use App\Repository\GradeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GradeRepository::class)]
class Grade
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $letterMark = null;

    #[ORM\Column]
    private ?int $trimester = null;

    #[ORM\Column(length: 255)]
    private ?string $trimesterName = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?int $absences = null;

    #[ORM\Column]
    private ?int $lates = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'grades')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $grades = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getLetterMark(): ?string
    {
        return $this->letterMark;
    }

    public function setLetterMark(?string $letterMark): static
    {
        $this->letterMark = $letterMark;

        return $this;
    }

    public function getTrimester(): ?int
    {
        return $this->trimester;
    }

    public function setTrimester(int $trimester): static
    {
        $this->trimester = $trimester;

        return $this;
    }

    public function getTrimesterName(): ?string
    {
        return $this->trimesterName;
    }

    public function setTrimesterName(string $trimesterName): static
    {
        $this->trimesterName = $trimesterName;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getAbsences(): ?int
    {
        return $this->absences;
    }

    public function setAbsences(int $absences): static
    {
        $this->absences = $absences;

        return $this;
    }

    public function getLates(): ?int
    {
        return $this->lates;
    }

    public function setLates(int $lates): static
    {
        $this->lates = $lates;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getGrades(): ?array
    {
        return $this->grades;
    }

    public function setGrades(?array $grades): static
    {
        $this->grades = $grades;

        return $this;
    }
}
