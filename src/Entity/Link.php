<?php

namespace App\Entity;

use App\Repository\LinkRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LinkRepository::class)
 */
class Link
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uri;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\OneToOne(targetEntity=Book::class, mappedBy="link", cascade={"persist", "remove"})
     */
    private $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

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
            $this->book->setLink(null);
        }

        // set the owning side of the relation if necessary
        if ($book !== null && $book->getLink() !== $this) {
            $book->setLink($this);
        }

        $this->book = $book;

        return $this;
    }
}
