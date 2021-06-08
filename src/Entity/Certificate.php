<?php

namespace App\Entity;

use App\Repository\CertificateRepository;
use App\Traits\BlameableEntityTrait;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CertificateRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Certificate
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;
    use BlameableEntityTrait;

    const TYPE_DEFAULT = 'Certificado';
    const TYPE_PARTICIPATION = 'Participación';
    const TYPE_CAPACITATION = 'Capacitación';
    const TYPE_APPROBATION = 'Aprobación';
    const TYPE_DIPLOMA = 'Diploma';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="certificates")
     */
    private $course;


    /**
     * @ORM\Column(type="integer")
     */
    private $hours;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $representative;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $representativePosition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trainerName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $trainerPosition;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $containsResolution;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class,cascade={"persist"})
     */
    private $logo;

     /**
     * @ORM\ManyToOne(targetEntity=Image::class,cascade={"persist"})
     */
    private $firm;
    /**
     * @ORM\ManyToOne(targetEntity=Level::class)
     */
    private $modality;

    /**
     * @ORM\OneToOne(targetEntity=Evaluation::class, inversedBy="certificateObj", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $evaluation;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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


    public function getHours(): ?int
    {
        return $this->hours;
    }

    public function setHours(int $hours): self
    {
        $this->hours = $hours;

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

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRepresentative(): ?string
    {
        return $this->representative;
    }

    public function setRepresentative(string $representative): self
    {
        $this->representative = $representative;

        return $this;
    }

    public function getRepresentativePosition(): ?string
    {
        return $this->representativePosition;
    }

    public function setRepresentativePosition(string $representativePosition): self
    {
        $this->representativePosition = $representativePosition;

        return $this;
    }

    public function getTrainerName(): ?string
    {
        return $this->trainerName;
    }

    public function setTrainerName(?string $trainerName): self
    {
        $this->trainerName = $trainerName;

        return $this;
    }

    public function getTrainerPosition(): ?string
    {
        return $this->trainerPosition;
    }

    public function setTrainerPosition(?string $trainerPosition): self
    {
        $this->trainerPosition = $trainerPosition;

        return $this;
    }

    public function getContainsResolution(): ?string
    {
        return $this->containsResolution;
    }

    public function setContainsResolution(?string $containsResolution): self
    {
        $this->containsResolution = $containsResolution;

        return $this;
    }

    public function getLogo(): ?Image
    {
        return $this->logo;
    }

    public function setLogo(?Image $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getFirm(): ?Image
    {
        return $this->firm;
    }

    public function setFirm(?Image $firm): self
    {
        $this->firm = $firm;

        return $this;
    }

    public function getModality(): ?Level
    {
        return $this->modality;
    }

    public function setModality(?Level $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    public function getEvaluation(): ?Evaluation
    {
        return $this->evaluation;
    }

    public function setEvaluation(Evaluation $evaluation): self
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    
}
