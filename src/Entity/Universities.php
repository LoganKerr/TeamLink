<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Universities
 *
 * @ORM\Table(name="universities")
 * @ORM\Entity
 */
class Universities
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
     * @ORM\Column(name="title", type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @var bool
     *
     * @ORM\Column(name="expired", type="boolean", nullable=false)
     */
    private $expired;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getExpired(): ?bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }


}
