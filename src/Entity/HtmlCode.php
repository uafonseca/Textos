<?php

namespace App\Entity;

use App\Repository\HtmlCodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HtmlCodeRepository::class)
 */
class HtmlCode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity=Book::class, mappedBy="htmlCode", cascade={"persist", "remove"})
     */
    private $book;

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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        // unset the owning side of the relation if necessary
        if ($book === null && $this->book !== null) {
            $this->book->setHtmlCode(null);
        }

        // set the owning side of the relation if necessary
        if ($book !== null && $book->getHtmlCode() !== $this) {
            $book->setHtmlCode($this);
        }

        $this->book = $book;

        return $this;
    }
}
