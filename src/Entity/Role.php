<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=RoleRepository::class)
 */
class Role
{
	use UuidEntityTrait;
	
	public const ROLE_ADMIN = 'ROLE_ADMIN';
	public const ROLE_USER = 'ROLE_USER';
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="rolesObject")
     */
    private $users;
	
	/**
	 * Role constructor.
	 */
	public function __construct ()
	{
		$this->uuid = Uuid::v1 ();
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

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }
    
    public function __toString(){
        return $this->rolename;
    }

}
