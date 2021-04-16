<?php

namespace App\Entity;

use App\Repository\IdentityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IdentityRepository::class)
 */
class Identity
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
    private $colorPrimary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorSecondary;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorSuccess;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorWarning;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $colorInfo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColorPrimary(): ?string
    {
        return $this->colorPrimary;
    }

    public function setColorPrimary(string $colorPrimary): self
    {
        $this->colorPrimary = $colorPrimary;

        return $this;
    }

    public function getColorSecondary(): ?string
    {
        return $this->colorSecondary;
    }

    public function setColorSecondary(string $colorSecondary): self
    {
        $this->colorSecondary = $colorSecondary;

        return $this;
    }

    public function getColorSuccess(): ?string
    {
        return $this->colorSuccess;
    }

    public function setColorSuccess(string $colorSuccess): self
    {
        $this->colorSuccess = $colorSuccess;

        return $this;
    }

    public function getColorWarning(): ?string
    {
        return $this->colorWarning;
    }

    public function setColorWarning(string $colorWarning): self
    {
        $this->colorWarning = $colorWarning;

        return $this;
    }

    public function getColorInfo(): ?string
    {
        return $this->colorInfo;
    }

    public function setColorInfo(string $colorInfo): self
    {
        $this->colorInfo = $colorInfo;

        return $this;
    }
}
