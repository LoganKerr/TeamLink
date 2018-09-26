<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RoleAssoc
 *
 * @ORM\Table(name="role_assoc")
 * @ORM\Entity
 */
class RoleAssoc
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
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="team_id", type="integer", nullable=false)
     */
    private $teamId;

    /**
     * @var int
     *
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     */
    private $roleId;

    /**
     * @var bool
     *
     * @ORM\Column(name="selected", type="boolean", nullable=false)
     */
    private $selected;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(?int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTeamId(): ?int
    {
        return $this->teamId;
    }

    public function setTeamId(int $teamId): self
    {
        $this->teamId = $teamId;

        return $this;
    }

    public function getRoleId(): ?int
    {
        return $this->roleId;
    }

    public function setRoleId(int $roleId): self
    {
        $this->roleId = $roleId;

        return $this;
    }

    public function getSelected(): ?bool
    {
        return $this->selected;
    }

    public function setSelected(bool $selected): self
    {
        $this->selected = $selected;

        return $this;
    }


}
