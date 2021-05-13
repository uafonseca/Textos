<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Activity
{

    /** @Var string */
    const TYPE_GENIALLY = 'Genially';

    /** @Var string */
    const TYPE_AUDIO = 'Audio';

    /** @Var string */
    const TYPE_VIDEO = 'Video';

    /** @Var string */
    const TYPE_YOUTUBE = 'Youtube';

    /** @var string  */
    const TYPE_FILE = 'Archivo';

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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $page;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

      /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
	 * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
	 */
	private $file;

    /**
     * @ORM\ManyToOne(targetEntity=Unit::class, inversedBy="activities",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

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

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function setPage(int $page): self
    {
        $this->page = $page;

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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
    
    /**
     * getType
     *
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }
    
    /**
     * setType
     *
     * @param  mixed $type
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

        
    /**
     * getFile
     *
     * @return Image
     */
    public function getFile(): ?Image
    {
        return $this->file;
    }

        
    /**
     * setFile
     *
     * @param  mixed $file
     * @return self
     */
    public function setFile(?Image $file): self
    {
        $this->file = $file;

        return $this;
    }
}
