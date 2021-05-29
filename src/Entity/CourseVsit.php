<?php

namespace App\Entity;

use App\Repository\CourseVsitRepository;
use App\Traits\BlameableEntityTrait;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourseVsitRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class CourseVsit
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="courseVsits")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="courseVsits")
     */
    private $course;

    /**
     * @ORM\Column(type="datetime")
     */
    private $moment;

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

    public function getCourse(): ?Book
    {
        return $this->course;
    }

    public function setCourse(?Book $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getMoment(): ?\DateTimeInterface
    {
        return $this->moment;
    }

    public function setMoment(\DateTimeInterface $moment): self
    {
        $this->moment = $moment;

        return $this;
    }
}
