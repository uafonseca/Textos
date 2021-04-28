<?php

namespace App\Entity;

use App\Repository\ChoiceAnswerRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChoiceAnswerRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class ChoiceAnswer
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
     * @ORM\ManyToOne(targetEntity=Choice::class, inversedBy="choiceAnswers")
     */
    private $choice;

    /**
     * @ORM\ManyToOne(targetEntity=SingleQuestion::class, inversedBy="choiceAnswers")
     */
    private $singleAnswer;

    /**
     * @ORM\ManyToOne(targetEntity=AnswerQuestion::class, inversedBy="choicesAnswers")
     */
    private $answerQuestion;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $singleAnswerText;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSelected;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoice(): ?Choice
    {
        return $this->choice;
    }

    public function setChoice(?Choice $choice): self
    {
        $this->choice = $choice;

        return $this;
    }

    public function getSingleAnswer(): ?SingleQuestion
    {
        return $this->singleAnswer;
    }

    public function setSingleAnswer(?SingleQuestion $singleAnswer): self
    {
        $this->singleAnswer = $singleAnswer;

        return $this;
    }

    public function getAnswerQuestion(): ?AnswerQuestion
    {
        return $this->answerQuestion;
    }

    public function setAnswerQuestion(?AnswerQuestion $answerQuestion): self
    {
        $this->answerQuestion = $answerQuestion;

        return $this;
    }

    public function getSingleAnswerText(): ?string
    {
        return $this->singleAnswerText;
    }

    public function setSingleAnswerText(string $singleAnswerText): self
    {
        $this->singleAnswerText = $singleAnswerText;

        return $this;
    }

    public function getIsSelected(): ?bool
    {
        return $this->isSelected;
    }

    public function setIsSelected(bool $isSelected): self
    {
        $this->isSelected = $isSelected;

        return $this;
    }

}
