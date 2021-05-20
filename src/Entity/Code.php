<?php

namespace App\Entity;

use App\Repository\CodeRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=CodeRepository::class)
 */
class Code
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
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $starDate;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="codes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="codes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxDays;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $unlimited;

    /**
     * @ORM\ManyToOne(targetEntity=CodeSalesData::class, inversedBy="codes", cascade={"persist"})
     *
     */
    private $salesData;

    /**
     * @ORM\Column(type="string", length=255, nullable = true)
     */
    private $token;

    public function __construct ()
    {
        $this->uuid = Uuid::v1 ();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getStarDate(): ?\DateTimeInterface
    {
        return $this->starDate;
    }

    public function setStarDate( $starDate): self
    {
        if( $starDate instanceof \DateTimeInterface)
            $this->starDate = $starDate;
        else 
            $this->starDate = new \DateTime($starDate);

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate($endDate): self
    {
        $this->endDate = $endDate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMaxDays(): ?int
    {
        return $this->maxDays;
    }

    public function setMaxDays(?int $maxDays): self
    {
        $this->maxDays = $maxDays;

        return $this;
    }

    public function getUnlimited(): ?bool
    {
        return $this->unlimited;
    }

    public function setUnlimited(bool $unlimited): self
    {
        $this->unlimited = $unlimited;

        return $this;
    }

    public function getSalesData(): ?CodeSalesData
    {
        return $this->salesData;
    }

    public function setSalesData(?CodeSalesData $salesData): self
    {
        $this->salesData = $salesData;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }
}
