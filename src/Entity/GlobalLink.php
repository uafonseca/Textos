<?php

namespace App\Entity;

use App\Repository\GlobalLinkRepository;
use App\Traits\BlameableEntityTrait;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GlobalLinkRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class GlobalLink
{
    use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;
    use BlameableEntityTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $url;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $imagen;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destination;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getImagen(): ?Image
    {
        return $this->imagen;
    }

    public function setImagen(?Image $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }
}
