<?php

namespace App\Entity;

use App\Repository\TermsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TermsRepository::class)
 */
class Terms
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
    private $terms;

    /**
     * @ORM\Column(type="text")
     */
    private $privacy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerms(): ?string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): self
    {
        $this->terms = $terms;

        return $this;
    }

    public function getPrivacy(): ?string
    {
        return $this->privacy;
    }

    public function setPrivacy(string $privacy): self
    {
        $this->privacy = $privacy;

        return $this;
    }
}
