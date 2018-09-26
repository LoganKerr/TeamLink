<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Faculty
 *
 * @ORM\Table(name="faculty")
 * @ORM\Entity
 */
class Faculty
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
     * @var string
     *
     * @ORM\Column(name="department", type="string", length=256, nullable=false)
     */
    private $department;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }


}
