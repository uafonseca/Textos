<?php

namespace App\Entity;

use App\Repository\ChoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChoiceRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Choice
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active = true;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="choices")
     */
    private $question;

    /**
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCorrect;


    /**
     * @ORM\OneToMany(targetEntity=ChoiceAnswer::class, mappedBy="choice")
     */
    private $choiceAnswers;

    public function __construct()
    {

        $this->choiceAnswers = new ArrayCollection();
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

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(string $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    /**
     * @return Collection|ChoiceAnswer[]
     */
    public function getChoiceAnswers(): Collection
    {
        return $this->choiceAnswers;
    }

    public function addChoiceAnswer(ChoiceAnswer $choiceAnswer): self
    {
        if (!$this->choiceAnswers->contains($choiceAnswer)) {
            $this->choiceAnswers[] = $choiceAnswer;
            $choiceAnswer->setChoice($this);
        }

        return $this;
    }

    public function removeChoiceAnswer(ChoiceAnswer $choiceAnswer): self
    {
        if ($this->choiceAnswers->removeElement($choiceAnswer)) {
            // set the owning side to null (unless already changed)
            if ($choiceAnswer->getChoice() === $this) {
                $choiceAnswer->setChoice(null);
            }
        }

        return $this;
    }
}
