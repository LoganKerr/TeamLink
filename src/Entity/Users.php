<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class Users
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="university_id", type="integer", nullable=true)
     */
    private $universityId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="student_id", type="integer", nullable=true)
     */
    private $studentId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="faculty_id", type="integer", nullable=true)
     */
    private $facultyId;

    /**
     * @var bool
     *
     * @ORM\Column(name="admin", type="boolean", nullable=false)
     */
    private $admin;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=256, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=256, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=256, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="passHash", type="string", length=256, nullable=false)
     */
    private $passhash;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUniversityId(): ?int
    {
        return $this->universityId;
    }

    public function setUniversityId(?int $universityId): self
    {
        $this->universityId = $universityId;

        return $this;
    }

    public function getStudentId(): ?int
    {
        return $this->studentId;
    }

    public function setStudentId(?int $studentId): self
    {
        $this->studentId = $studentId;

        return $this;
    }

    public function getFacultyId(): ?int
    {
        return $this->facultyId;
    }

    public function setFacultyId(?int $facultyId): self
    {
        $this->facultyId = $facultyId;

        return $this;
    }

    public function getAdmin(): ?bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPasshash(): ?string
    {
        return $this->passhash;
    }

    public function setPasshash(string $passhash): self
    {
        $this->passhash = $passhash;

        return $this;
    }

}
