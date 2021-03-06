<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=UnitRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Unit
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="units")
     */
    private $book;

    /**
     * @ORM\OneToOne(targetEntity=PDF::class, cascade={"persist", "remove"})
     */
    private $pdf;

   /**
     * @ORM\Column(type="text", nullable = true)
     */
    private $html5Code;
    /**
     * @ORM\OneToMany(targetEntity=Activity::class, mappedBy="unit", orphanRemoval=true,cascade={"persist"})
     */
    private $activities;

    /**
     * @ORM\OneToOne(targetEntity=Evaluation::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $evaluation;


    public function __construct()
    {
        $this->uuid = Uuid::v1();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getPDF(): ?PDF
    {
        return $this->pdf;
    }

    public function setPDF(?PDF $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

    /**
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
            $activity->setUnit($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getUnit() === $this) {
                $activity->setUnit(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(?Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * Get the value of html5Code
     */ 
    public function getHtml5Code()
    {
        return $this->html5Code;
    }

    /**
     * Set the value of html5Code
     *
     * @return  self
     */ 
    public function setHtml5Code($html5Code)
    {
        $this->html5Code = $html5Code;

        return $this;
    }
}
