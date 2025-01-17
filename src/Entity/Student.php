<?php

namespace App\Entity;

use App\Enum\GenderEnum;
use App\Enum\LevelEnum;
use App\Enum\ProgramEnum;
use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $studentId = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $relatedUser = null;

    #[ORM\Column(enumType: LevelEnum::class)]
    private ?LevelEnum $level = null;

    #[ORM\Column(nullable: true, enumType: ProgramEnum::class)]
    private ?ProgramEnum $program = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $myGesCredentialsToken = null;

    #[ORM\Column(length: 255)]
    private ?string $ine = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $personalEmail = null;

    #[ORM\Column(length: 255)]
    private ?string $birthplace = null;

    #[ORM\Column(length: 255)]
    private ?string $birthCountry = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $nationality = null;

    #[ORM\Column(enumType: GenderEnum::class)]
    private ?GenderEnum $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePicture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStudentId(): ?string
    {
        return $this->studentId;
    }

    public function setStudentId(string $studentId): static
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getRelatedUser(): ?User
    {
        return $this->relatedUser;
    }

    public function setRelatedUser(User $relatedUser): static
    {
        $this->relatedUser = $relatedUser;

        return $this;
    }

    public function getLevel(): ?LevelEnum
    {
        return $this->level;
    }

    public function setLevel(LevelEnum $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getProgram(): ?ProgramEnum
    {
        return $this->program;
    }

    public function setProgram(ProgramEnum $program): static
    {
        $this->program = $program;

        return $this;
    }

    public function getMyGesCredentialsToken(): ?string
    {
        return $this->myGesCredentialsToken;
    }

    public function setMyGesCredentialsToken(string $myGesCredentialsToken): static
    {
        $this->myGesCredentialsToken = $myGesCredentialsToken;

        return $this;
    }

    public function getIne(): ?string
    {
        return $this->ine;
    }

    public function setIne(string $ine): static
    {
        $this->ine = $ine;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPersonalEmail(): ?string
    {
        return $this->personalEmail;
    }

    public function setPersonalEmail(string $personalEmail): static
    {
        $this->personalEmail = $personalEmail;

        return $this;
    }

    public function getBirthplace(): ?string
    {
        return $this->birthplace;
    }

    public function setBirthplace(string $birthplace): static
    {
        $this->birthplace = $birthplace;

        return $this;
    }

    public function getBirthCountry(): ?string
    {
        return $this->birthCountry;
    }

    public function setBirthCountry(string $birthCountry): static
    {
        $this->birthCountry = $birthCountry;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(GenderEnum $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }
}
