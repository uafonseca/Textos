<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use App\Traits\BlameableEntityTrait;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class UserGroup
{

    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;
    use BlameableEntityTrait;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $groupName;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $startDate;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userGroups")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="userGroups")
     * @ORM\JoinColumn(nullable=true)
     */
    private $modality;

    /**
     * @ORM\OneToMany(targetEntity=Mail::class, mappedBy="userGroup")
     */
    private $mails;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $chatDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $videoLink;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $hour;

       /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $invitation;

    /**
     * @ORM\OneToMany(targetEntity=Invitation::class, mappedBy="userGroup")
     */
    private $invitations;

    public function __construct()
    {
        $this->uuid = Uuid::v1();
        $this->users = new ArrayCollection();
        $this->mails = new ArrayCollection();
        $this->invitations = new ArrayCollection();
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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getChatDate(): ?\DateTimeInterface
    {
        return $this->chatDate;
    }

    public function setChatDate(?\DateTimeInterface $chatDate): self
    {
        $this->chatDate = $chatDate;

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(?\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }
    

    /**
     * Get the value of invitation
     */ 
    public function getInvitation()
    {
        return $this->invitation;
    }

    /**
     * Set the value of invitation
     *
     * @return  self
     */ 
    public function setInvitation($invitation)
    {
        $this->invitation = $invitation;

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
            $invitation->setUserGroup($this);
        }

        return $this;
    }

    public function removeInvitation(Invitation $invitation): self
    {
        if ($this->invitations->removeElement($invitation)) {
            // set the owning side to null (unless already changed)
            if ($invitation->getUserGroup() === $this) {
                $invitation->setUserGroup(null);
            }
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function __toString():string{
        return $this->getGroupName();
    }

    /**
     * Get the value of enabled
     */ 
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set the value of enabled
     *
     * @return  self
     */ 
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }
}
