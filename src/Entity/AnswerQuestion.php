<?php

namespace App\Entity;

use App\Repository\AnswerQuestionRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerQuestionRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class AnswerQuestion
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
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="answerQuestions")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity=Answer::class, inversedBy="answerQuestions", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;

    /**
     * @ORM\OneToMany(targetEntity=ChoiceAnswer::class, mappedBy="answerQuestion",cascade={"persist"})
     */
    private $choicesAnswers;

    public function __construct()
    {
        $this->choicesAnswers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }


    /**
     * @ORM\PrePersist()
     */
    public function updateAnswer(): void
    {
        $this->answer->updateProgress();

//        if ($this->question->isLastQuestion()) {
//            $this->answer->setEndDate(new \DateTime());
//        }
    }

    /**
     * @return Collection|ChoiceAnswer[]
     */
    public function getChoicesAnswers(): Collection
    {
        return $this->choicesAnswers;
    }

    public function addChoicesAnswer(ChoiceAnswer $choicesAnswer): self
    {
        if (!$this->choicesAnswers->contains($choicesAnswer)) {
            $this->choicesAnswers[] = $choicesAnswer;
            $choicesAnswer->setAnswerQuestion($this);
        }

        return $this;
    }

    public function removeChoicesAnswer(ChoiceAnswer $choicesAnswer): self
    {
        if ($this->choicesAnswers->removeElement($choicesAnswer)) {
            // set the owning side to null (unless already changed)
            if ($choicesAnswer->getAnswerQuestion() === $this) {
                $choicesAnswer->setAnswerQuestion(null);
            }
        }

        return $this;
    }
}
