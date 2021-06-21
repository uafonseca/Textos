<?php

namespace App\Entity;

use App\Repository\MailResponseRepository;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @ORM\Entity(repositoryClass=MailResponseRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class MailResponse
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
     * @ORM\ManyToOne(targetEntity=Mail::class, inversedBy="mailResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="mailResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="text")
     */
    private $context;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $attached;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $evaluation;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?Mail
    {
        return $this->mail;
    }

    public function setMail(?Mail $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

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

    public function getEvaluation(): ?int
    {
        return $this->evaluation;
    }

    public function setEvaluation(?int $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }
}
