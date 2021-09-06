<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Role
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;

    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_DOCENTE = 'ROLE_PROFESOR';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rolename;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="rolesObject")
     */
    private $users;


    /**
     * Role constructor.
     */
    public function __construct()
    {
        $this->uuid = Uuid::v1();
        $this->users = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRolename(): ?string
    {
        return $this->rolename;
    }

    public function setRolename(string $rolename): self
    {
        $this->rolename = $rolename;

        return $this;
    }



    public function __toString()
    {
        return $this->rolename;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addRolesObject($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeRolesObject($this);
        }

        return $this;
    }

}
