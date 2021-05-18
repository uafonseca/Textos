<?php

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Traits\TimestampableTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Serializable;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="Existe un usuario registrado con este nombre")
 * @UniqueEntity(fields={"email"}, message="Existe un correo registrado con este nombre")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, Serializable
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;


    /**
     *
     * @ORM\Column(type="string", length=180)
     */
    protected $name;

    /**
     *
     * @ORM\Column(type="string", length=180)
     */
    protected $username;

    /**
     *
     * @ORM\Column(type="string", length=180)
     */
    protected $firstName;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scoholName;

    /**
     * @ORM\OneToOne(targetEntity=Estudiante::class, cascade={"persist", "remove"})
     */
    private $student;

    /**
     * @ORM\OneToOne(targetEntity=Profesor::class, cascade={"persist", "remove"})
     */
    private $profesor;

    /**
     *
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $provincia;

    /**
     *
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $canton;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, inversedBy="users")
     */
    private $rolesObject;

    /**
     * @ORM\OneToMany(targetEntity=Code::class, mappedBy="user", orphanRemoval=true)
     */
    private $codes;

    /**
     * @ORM\OneToMany(targetEntity=Answer::class, mappedBy="owner")
     */
    private $answers;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class)
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=State::class)
     */
    private $city;


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->rolesObject = new ArrayCollection();
        $this->codes = new ArrayCollection();
        $this->answers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getusername(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $rolenames = [];
        foreach ($this->rolesObject as $role)
            if ($role instanceof Role)
                $rolenames[] = $role->getRolename();

        return count($rolenames) > 0 ? array_unique($rolenames) : ['ROLE_USER'];
    }

    /**
     * @param Role $role
     *
     * @return User
     */
    public function addRoleObj(Role $role): self
    {
        if ($this->rolesObject instanceof Collection && !$this->rolesObject->contains($role)) {

            die;
            $this->rolesObject->add($role);
        } else if (is_array($this->rolesObject))
            $this->rolesObject[] = $role;
        return $this;
    }

    /**
     * @param $rolesObject
     *
     * @return User
     */
    public function setRoles($rolesObject): self
    {
        $this->rolesObject = $rolesObject;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setusername($username)
    {
        $this->username = $username;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getStudent(): ?Estudiante
    {
        return $this->student;
    }

    public function setStudent(?Estudiante $student): self
    {
        $this->student = $student;

        return $this;
    }

    public function getProfesor(): ?Profesor
    {
        return $this->profesor;
    }

    public function setProfesor(?Profesor $profesor): self
    {
        $this->profesor = $profesor;

        return $this;
    }

    public function getScoholName(): ?string
    {
        return $this->scoholName;
    }

    public function setScoholName(?string $scoholName): self
    {
        $this->scoholName = $scoholName;

        return $this;
    }


    public function getScoholLocality(): ?string
    {
        return $this->scoholLocality;
    }

    public function setScoholLocality(?string $scoholLocality): self
    {
        $this->scoholLocality = $scoholLocality;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia($provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getCanton()
    {
        return $this->canton;
    }

    public function setCanton($canton): self
    {
        $this->canton = $canton;

        return $this;
    }

    public function getAvatar(): ?Image
    {
        return $this->avatar;
    }

    public function setAvatar(?Image $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRolesObject(): Collection
    {
        return $this->rolesObject;
    }

    public function addRolesObject(Role $rolesObject): self
    {
        if (!$this->rolesObject->contains($rolesObject)) {
            $this->rolesObject[] = $rolesObject;
        }

        return $this;
    }

    public function removeRolesObject(Role $rolesObject): self
    {
        $this->rolesObject->removeElement($rolesObject);

        return $this;
    }

    public function serialize()
    {
        $this->avatar = base64_encode($this->avatar);
        return serialize(array(
            $this->id,
            $this->uuid,
            $this->username,
            $this->email,
            $this->password,
            $this->avatar = base64_encode($this->avatar)

        ));
    }

    public function unserialize($serialized)
    {

        list(
            $this->id,
            $this->uuid,
            $this->username,
            $this->email,
            $this->password,
            $this->avatar,
            ) = unserialize($serialized);

        $this->avatar = base64_decode($this->avatar);
    }

    /**
     * @return Collection|Code[]
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    public function addCode(Code $code): self
    {
        if (!$this->codes->contains($code)) {
            $this->codes[] = $code;
            $code->setUser($this);
        }

        return $this;
    }

    public function removeCode(Code $code): self
    {
        if ($this->codes->removeElement($code)) {
            // set the owning side to null (unless already changed)
            if ($code->getUser() === $this) {
                $code->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setOwner($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getOwner() === $this) {
                $answer->setOwner(null);
            }
        }

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?State
    {
        return $this->city;
    }

    public function setCity(?State $city): self
    {
        $this->city = $city;

        return $this;
    }
}
