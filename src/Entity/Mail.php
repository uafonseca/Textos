<?php

namespace App\Entity;

use App\Repository\MailRepository;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MailRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Mail
{
    use UuidEntityTrait;
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $subject;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mailsSend")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="mailsReceived")
     */
    private $recipients;

    /**
     * @ORM\Column(type="text")
     */
    private $context;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class,cascade={"persist"})
     */
    private $attached;

    /**
     * @ORM\ManyToOne(targetEntity=UserGroup::class, inversedBy="mails")
     */
    private $userGroup;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $homework;

    /**
     * @ORM\OneToMany(targetEntity=MailResponse::class, mappedBy="mail", orphanRemoval=true)
     */
    private $mailResponses;

    public function __construct()
    {
        $this->recipients = new ArrayCollection();
        $this->mailResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRecipients(): Collection
    {
        return $this->recipients;
    }

    public function addRecipient(User $recipient): self
    {
        if (!$this->recipients->contains($recipient)) {
            $this->recipients[] = $recipient;
        }

        return $this;
    }

    public function removeRecipient(User $recipient): self
    {
        $this->recipients->removeElement($recipient);

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getAttached(): ?Image
    {
        return $this->attached;
    }

    public function setAttached(?Image $attached): self
    {
        $this->attached = $attached;

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

    public function getHomework(): ?bool
    {
        return $this->homework;
    }

    public function setHomework(?bool $homework): self
    {
        $this->homework = $homework;

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
            $mailResponse->setMail($this);
        }

        return $this;
    }

    public function removeMailResponse(MailResponse $mailResponse): self
    {
        if ($this->mailResponses->removeElement($mailResponse)) {
            // set the owning side to null (unless already changed)
            if ($mailResponse->getMail() === $this) {
                $mailResponse->setMail(null);
            }
        }

        return $this;
    }
}
