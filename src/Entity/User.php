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
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    protected $plainPassword;


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
     * @ORM\ManyToMany(targetEntity=UserGroup::class, mappedBy="users", cascade={"persist"})
     */
    private $userGroups;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cedula;

    /**
     * @ORM\OneToMany(targetEntity=Mail::class, mappedBy="sender", orphanRemoval=true)
     */
    private $mailsSend;

    /**
     * @ORM\ManyToMany(targetEntity=Mail::class, mappedBy="recipients")
     */
    private $mailsReceived;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $sessionId;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $lastLogin;

    /**
     * @ORM\OneToMany(targetEntity=CourseVsit::class, mappedBy="user")
     */
    private $courseVsits;

    /**
     * @ORM\OneToMany(targetEntity=MailResponse::class, mappedBy="User")
     */
    private $mailResponses;

    /**
     * @ORM\OneToMany(targetEntity=Book::class, mappedBy="user")
     */
    private $freeBooks;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="user")
     */
    private $invitations;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $facebookId;

    


    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->rolesObject = new ArrayCollection();
        $this->codes = new ArrayCollection();
        $this->answers = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->mailsSend = new ArrayCollection();
        $this->mailsReceived = new ArrayCollection();
        $this->courseVsits = new ArrayCollection();
        $this->mailResponses = new ArrayCollection();
        $this->freeBooks = new ArrayCollection();
        $this->invitations = new ArrayCollection();
        
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
        return serialize(array(
            $this->id,
            $this->uuid,
            $this->username,
            $this->email,
            $this->password,
            // $this->avatar = base64_encode($this->avatar)

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
            // $this->avatar,
            ) = unserialize($serialized);

        // $this->avatar = base64_decode($this->avatar);
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

    public function getPlainPassword() {
		return $this->plainPassword;
	}

	public function setPlainPassword( $plainPassword) {
                                                                     $this->plainPassword = $plainPassword;
                                                                   }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->addUser($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            $userGroup->removeUser($this);
        }

        return $this;
    }

    public function getCedula(): ?string
    {
        return $this->cedula;
    }

    public function setCedula(string $cedula): self
    {
        $this->cedula = $cedula;

        return $this;
    }

    /**
     * @return Collection|Mail[]
     */
    public function getMailsSend(): Collection
    {
        return $this->mailsSend;
    }

    public function addMailsSend(Mail $mailsSend): self
    {
        if (!$this->mailsSend->contains($mailsSend)) {
            $this->mailsSend[] = $mailsSend;
            $mailsSend->setSender($this);
        }

        return $this;
    }

    public function removeMailsSend(Mail $mailsSend): self
    {
        if ($this->mailsSend->removeElement($mailsSend)) {
            // set the owning side to null (unless already changed)
            if ($mailsSend->getSender() === $this) {
                $mailsSend->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mail[]
     */
    public function getMailsReceived(): Collection
    {
        return $this->mailsReceived;
    }

    public function addMailsReceived(Mail $mailsReceived): self
    {
        if (!$this->mailsReceived->contains($mailsReceived)) {
            $this->mailsReceived[] = $mailsReceived;
            $mailsReceived->addRecipient($this);
        }

        return $this;
    }

    public function removeMailsReceived(Mail $mailsReceived): self
    {
        if ($this->mailsReceived->removeElement($mailsReceived)) {
            $mailsReceived->removeRecipient($this);
        }

        return $this;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): self
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(\DateTimeInterface $lastLogin): self
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * @return Collection|CourseVsit[]
     */
    public function getCourseVsits(): Collection
    {
        return $this->courseVsits;
    }

    public function addCourseVsit(CourseVsit $courseVsit): self
    {
        if (!$this->courseVsits->contains($courseVsit)) {
            $this->courseVsits[] = $courseVsit;
            $courseVsit->setUser($this);
        }

        return $this;
    }

    public function removeCourseVsit(CourseVsit $courseVsit): self
    {
        if ($this->courseVsits->removeElement($courseVsit)) {
            // set the owning side to null (unless already changed)
            if ($courseVsit->getUser() === $this) {
                $courseVsit->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MailResponse[]
     */
    public function getMailResponses(): Collection
    {
        return $this->mailResponses;
    }

    public function addMailResponse(MailResponse $mailResponse): self
    {
        if (!$this->mailResponses->contains($mailResponse)) {
            $this->mailResponses[] = $mailResponse;
            $mailResponse->setUser($this);
        }

        return $this;
    }

    public function removeMailResponse(MailResponse $mailResponse): self
    {
        if ($this->mailResponses->removeElement($mailResponse)) {
            // set the owning side to null (unless already changed)
            if ($mailResponse->getUser() === $this) {
                $mailResponse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getFreeBooks(): Collection
    {
        return $this->freeBooks;
    }

    public function addFreeBook(Book $freeBook): self
    {
        if (!$this->freeBooks->contains($freeBook)) {
            $this->freeBooks[] = $freeBook;
            $freeBook->setUser($this);
        }

        return $this;
    }

    public function removeFreeBook(Book $freeBook): self
    {
        if ($this->freeBooks->removeElement($freeBook)) {
            // set the owning side to null (unless already changed)
            if ($freeBook->getUser() === $this) {
                $freeBook->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Invitation[]
     */
    public function getInvitations(): Collection
    {
        return $this->invitations;
    }

    public function addInvitation(Invitation $invitation): self
    {
        if (!$this->invitations->contains($invitation)) {
            $this->invitations[] = $invitation;
            $invitation->setUser($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getUser() === $this) {
                $invitation->setUser(null);
            }
        }

        return $this;
    }

    public function getFacebookId(): ?int
    {
        return $this->facebookId;
    }

    public function setFacebookId(?int $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }
    
}
