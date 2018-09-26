<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Students
 *
 * @ORM\Table(name="students")
 * @ORM\Entity
 */
class Students
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
     * @ORM\Column(name="major", type="string", length=256, nullable=false)
     */
    private $major;

    /**
     * @var string
     *
     * @ORM\Column(name="interests", type="string", length=256, nullable=false)
     */
    private $interests;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMajor(): ?string
    {
        return $this->major;
    }

    public function setMajor(string $major): self
    {
        $this->major = $major;

        return $this;
    }

    public function getInterests(): ?string
    {
        return $this->interests;
    }

    public function setInterests(string $interests): self
    {
        $this->interests = $interests;

        return $this;
    }


}
