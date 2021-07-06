<?php

namespace App\Entity;

use App\Repository\InvitationRepository;
use App\Traits\BlameableEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Invitation
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use BlameableEntityTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="invitations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=UserGroup::class, inversedBy="invitations")
     */
    private $userGroup;

    /**
     * @ORM\Column(type="boolean")
     */
    private $acept = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUserGroup(): ?UserGroup
    {
        return $this->userGroup;
    }

    public function setUserGroup(?UserGroup $userGroup): self
    {
        $this->userGroup = $userGroup;

        return $this;
    }

    public function getAcept(): ?bool
    {
        return $this->acept;
    }

    public function setAcept(bool $acept): self
    {
        $this->acept = $acept;

        return $this;
    }
}
