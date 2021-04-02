<?php

namespace App\Entity;

use App\Repository\FinancialDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FinancialDetailsRepository::class)
 */
class FinancialDetails
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
    private $acountName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dni;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $acountNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $intitution;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $acountType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contact;

    /**
     * @ORM\Column(type="text")
     */
    private $paypalUrlComplete;

    /**
     * @ORM\Column(type="text")
     */
    private $PaypalUrlCancel;

    /**
     * @ORM\Column(type="text")
     */
    private $paypalHtmlCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAcountName(): ?string
    {
        return $this->acountName;
    }

    public function setAcountName(string $acountName): self
    {
        $this->acountName = $acountName;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): self
    {
        $this->dni = $dni;

        return $this;
    }

    public function getAcountNumber(): ?string
    {
        return $this->acountNumber;
    }

    public function setAcountNumber(string $acountNumber): self
    {
        $this->acountNumber = $acountNumber;

        return $this;
    }

    public function getIntitution(): ?string
    {
        return $this->intitution;
    }

    public function setIntitution(string $intitution): self
    {
        $this->intitution = $intitution;

        return $this;
    }

    public function getAcountType(): ?string
    {
        return $this->acountType;
    }

    public function setAcountType(string $acountType): self
    {
        $this->acountType = $acountType;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getPaypalUrlComplete(): ?string
    {
        return $this->paypalUrlComplete;
    }

    public function setPaypalUrlComplete(string $paypalUrlComplete): self
    {
        $this->paypalUrlComplete = $paypalUrlComplete;

        return $this;
    }

    public function getPaypalUrlCancel(): ?string
    {
        return $this->PaypalUrlCancel;
    }

    public function setPaypalUrlCancel(string $PaypalUrlCancel): self
    {
        $this->PaypalUrlCancel = $PaypalUrlCancel;

        return $this;
    }

    public function getPaypalHtmlCode(): ?string
    {
        return $this->paypalHtmlCode;
    }

    public function setPaypalHtmlCode(string $paypalHtmlCode): self
    {
        $this->paypalHtmlCode = $paypalHtmlCode;

        return $this;
    }
}
