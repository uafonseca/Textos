<?php

namespace App\Entity;

use App\Repository\CodeSalesDataRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CodeSalesDataRepository::class)
 */
class CodeSalesData
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
    private $details;

    /**
     * @ORM\Column(type="float")
     */
    private $value;

    /**
     * @ORM\Column(type="float")
     */
    private $iva;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     */
    private $total;

    /**
     * @ORM\OneToOne(targetEntity=FinancialDetails::class, cascade={"persist", "remove"})
     */
    private $financialDetails;

    /**
     * @ORM\OneToMany(targetEntity=Code::class, mappedBy="salesData", orphanRemoval=true,  cascade={"persist", "remove"})
     */
    private $codes;

    /**
     * CodeSalesData constructor.
     */
    public function __construct()
    {
        $this->codes =  new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getIva(): ?float
    {
        return $this->iva;
    }

    public function setIva(float $iva): self
    {
        $this->iva = $iva;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getFinancialDetails(): ?FinancialDetails
    {
        return $this->financialDetails;
    }

    public function setFinancialDetails(?FinancialDetails $financialDetails): self
    {
        $this->financialDetails = $financialDetails;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getCodes(): ?ArrayCollection
    {
        return $this->codes;
    }

    /**
     * @param ArrayCollection $codes
     * @return $this
     */
    public function setCodes(ArrayCollection $codes): self
    {
        $this->codes = $codes;
        return $this;
    }


}
