<?php

namespace App\Entity;

use App\Repository\SingleQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SingleQuestionRepository::class)
 */
class SingleQuestion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="singleQuestions")
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=ChoiceAnswer::class, mappedBy="singleAnswer")
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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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
            $choiceAnswer->setSingleAnswer($this);
        }

        return $this;
    }

    public function removeChoiceAnswer(ChoiceAnswer $choiceAnswer): self
    {
        if ($this->choiceAnswers->removeElement($choiceAnswer)) {
            // set the owning side to null (unless already changed)
            if ($choiceAnswer->getSingleAnswer() === $this) {
                $choiceAnswer->setSingleAnswer(null);
            }
        }

        return $this;
    }
}
