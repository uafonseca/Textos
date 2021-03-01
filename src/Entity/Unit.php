<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * @ORM\Entity(repositoryClass=UnitRepository::class)
 */
class Unit
{
    use UuidEntityTrait;
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
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="units")
     */
    private $book;

        /**
     * @ORM\OneToOne(targetEntity=PDF::class, cascade={"persist", "remove"})
     */
    private $pdf;

    public function __construct()
	{
		$this->uuid = Uuid::v1();
	}

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

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getPDF(): ?PDF
    {
        return $this->pdf;
    }

    public function setPDF(?PDF $pdf): self
    {
        $this->pdf = $pdf;

        return $this;
    }

}
