<?php

namespace App\Entity;

use App\Repository\BookRepository;
use App\Traits\UuidEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Book
{
    use UuidEntityTrait;
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="books")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=SchoolStage::class, inversedBy="books")
     */
    private $stage;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="books")
     */
    private $level;

    /**
     * @ORM\Column(type="array")
     */
    private $source;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $portada;

    /**
     * @ORM\OneToOne(targetEntity=Link::class, inversedBy="book", cascade={"persist", "remove"})
     */
    private $link;

    /**
     * @ORM\OneToOne(targetEntity=HtmlCode::class, inversedBy="book", cascade={"persist", "remove"})
     */
    private $htmlCode;

    /**
     * @ORM\OneToMany(targetEntity=Unit::class, mappedBy="book")
     */
    private $units;


    public function __construct()
    {
        $this->uuid = Uuid::v1();
        $this->source = [];
        $this->units = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getStage(): ?SchoolStage
    {
        return $this->stage;
    }

    public function setStage(?SchoolStage $stage): self
    {
        $this->stage = $stage;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getSource(): ?array
    {
        return $this->source;
    }

    public function setSource(array $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getPortada(): ?Image
    {
        return $this->portada;
    }

    public function setPortada(?Image $portada): self
    {
        $this->portada = $portada;

        return $this;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getHtmlCode(): ?HtmlCode
    {
        return $this->htmlCode;
    }

    public function setHtmlCode(?HtmlCode $htmlCode): self
    {
        $this->htmlCode = $htmlCode;

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->units->contains($unit)) {
            $this->units[] = $unit;
            $unit->setBook($this);
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        if ($this->units->removeElement($unit)) {
            // set the owning side to null (unless already changed)
            if ($unit->getBook() === $this) {
                $unit->setBook(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->title;
    }
}
