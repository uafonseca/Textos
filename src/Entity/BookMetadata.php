<?php

namespace App\Entity;

use App\Repository\BookMetadataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookMetadataRepository::class)
 */
class BookMetadata
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dedication;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transcription;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $learning;

    /**
     * @ORM\OneToOne(targetEntity=Intitution::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $intitution;

    /**
     * @ORM\Column(type="text")
     */
    private $introduction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDedication(): ?string
    {
        return $this->dedication;
    }

    public function setDedication(string $dedication): self
    {
        $this->dedication = $dedication;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getTranscription(): ?string
    {
        return $this->transcription;
    }

    public function setTranscription(string $transcription): self
    {
        $this->transcription = $transcription;

        return $this;
    }

    public function getLearning(): ?string
    {
        return $this->learning;
    }

    public function setLearning(string $learning): self
    {
        $this->learning = $learning;

        return $this;
    }

    public function getIntitution(): ?Intitution
    {
        return $this->intitution;
    }

    public function setIntitution(Intitution $intitution): self
    {
        $this->intitution = $intitution;

        return $this;
    }

    public function getIntroduction(): ?string
    {
        return $this->introduction;
    }

    public function setIntroduction(string $introduction): self
    {
        $this->introduction = $introduction;

        return $this;
    }
}
