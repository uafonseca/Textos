<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class UserGroup
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
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="userGroups")
     */
    private $course;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $groupName;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;



    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userGroups")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="userGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $modality;

    /**
     * @ORM\OneToMany(targetEntity=Mail::class, mappedBy="userGroup")
     */
    private $mails;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->mails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Book
    {
        return $this->course;
    }

    public function setCourse(?Book $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getGroupName(): ?string
    {
        return $this->groupName;
    }

    public function setGroupName(string $groupName): self
    {
        $this->groupName = $groupName;

        return $this;
    }

    public function getStartDate()
    {
    
        return $this->startDate;
    }

    public function setStartDate($startDate): self
    {
        if ($startDate instanceof \DateTimeInterface)
            $this->startDate = $startDate;
        else
            $this->startDate = new \DateTime($startDate);
        return $this;
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
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    public function getModality(): ?Level
    {
        return $this->modality;
    }

    public function setModality(?Level $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    /**
     * @return Collection|Mail[]
     */
    public function getMails(): Collection
    {
        return $this->mails;
    }

    public function addMail(Mail $mail): self
    {
        if (!$this->mails->contains($mail)) {
            $this->mails[] = $mail;
            $mail->setUserGroup($this);
        }

        return $this;
    }

    public function removeMail(Mail $mail): self
    {
        if ($this->mails->removeElement($mail)) {
            // set the owning side to null (unless already changed)
            if ($mail->getUserGroup() === $this) {
                $mail->setUserGroup(null);
            }
        }

        return $this;
    }
}
