<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnswerRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Answer
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
     * @ORM\Column(type="integer")
     */
    private $progress;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=Evaluation::class, inversedBy="answers")
     */
    private $evaluation;

    /**
     * @ORM\Column(type="integer")
     */
    private $attemptsMade = 0;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="answers")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=AnswerQuestion::class, mappedBy="answer")
     * @ORM\JoinColumn(nullable=false)
     */
    private $answerQuestions;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $timeLeft;


    public function __construct()
    {
        $this->progress = 0;
        $this->answerQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgress(): ?int
    {
        return $this->progress;
    }

    public function setProgress(int $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|AnswerQuestion[]
     */
    public function getAnswerQuestions(): Collection
    {
        return $this->answerQuestions;
    }

    public function addAnswerQuestion(AnswerQuestion $answerQuestion): self
    {
        if (!$this->answerQuestions->contains($answerQuestion)) {
            $this->answerQuestions[] = $answerQuestion;
            $answerQuestion->setAnswer($this);
        }

        return $this;
    }

    public function removeAnswerQuestion(AnswerQuestion $answerQuestion): self
    {
        if ($this->answerQuestions->removeElement($answerQuestion)) {
            // set the owning side to null (unless already changed)
            if ($answerQuestion->getAnswer() === $this) {
                $answerQuestion->setAnswer(null);
            }
        }

        return $this;
    }
    public function updateProgress(): void
    {
        $this->progress = (int) ($this->answerQuestions->count() * 100 / $this->evaluation->getQuestions()->count());
    }

    /**
     * @return int
     */
    public function getAttemptsMade(): ?int
    {
        return $this->attemptsMade;
    }

    /**
     * @param int $attemptsMade
     */
    public function setAttemptsMade(int $attemptsMade): void
    {
        $this->attemptsMade = $attemptsMade;
    }

    public function getTimeLeft(): ?\DateTimeInterface
    {
        return $this->timeLeft;
    }

    public function setTimeLeft(?\DateTimeInterface $timeLeft): self
    {
        $this->timeLeft = $timeLeft;

        return $this;
    }

    public function getPoints(): array
    {
        $points = 0;
        foreach ($this->getAnswerQuestions() as $answerQuestion) {
            foreach ($answerQuestion->getChoicesAnswers() as $choicesAnswer)
                if ($choicesAnswer->getIsSelected()){
                    if ($choicesAnswer->getChoice()->getIsCorrect())
                        $points += $choicesAnswer->getChoice()->getValue();
                }
        }

        return[
            'status' => $points >= $this->evaluation->getPercentage() ? '<span class="text-success">APROBADO</span>' : '<span class="text-danger">NO APROBADO</span>',
            'points' => $points
        ] ;
    }

}
