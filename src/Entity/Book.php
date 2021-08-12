<?php

namespace App\Entity;

use App\Repository\BookRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\TimestampableTrait;
use App\Traits\UuidEntityTrait;
use App\Traits\BlameableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;


/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Book
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
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     */
    private $banner;

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

    /**
     * @ORM\OneToMany(targetEntity=Code::class, mappedBy="book", orphanRemoval=true)
     */
    private $codes;

    /**
     * @ORM\OneToOne(targetEntity=BookMetadata::class, cascade={"persist", "remove"})
     */
    private $metadata;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $initialDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modality;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="course")
     */
    private $userGroups;

    /**
     * @ORM\OneToMany(targetEntity=CourseVsit::class, mappedBy="course")
     */
    private $courseVsits;

    /**
     * @ORM\OneToMany(targetEntity=Certificate::class, mappedBy="course")
     */
    private $certificates;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $free;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="freeBooks")
     */
    private $freeUsers;




    public function __construct()
    {
        $this->uuid = Uuid::v1();
        $this->source = [];
        $this->units = new ArrayCollection();
        $this->codes = new ArrayCollection();
        $this->userGroups = new ArrayCollection();
        $this->courseVsits = new ArrayCollection();
        $this->certificates = new ArrayCollection();
        $this->freeUsers = new ArrayCollection();
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

    public function getBanner(): ?Image
    {
        return $this->banner;
    }

    public function setBanner(?Image $banner): self
    {
        $this->banner = $banner;

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

    /**
     * @return Collection|Code[]
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    public function addCode(Code $code): self
    {
        if (!$this->codes->contains($code)) {
            $this->codes[] = $code;
            $code->setBook($this);
        }

        return $this;
    }

    public function removeCode(Code $code): self
    {
        if ($this->codes->removeElement($code)) {
            // set the owning side to null (unless already changed)
            if ($code->getBook() === $this) {
                $code->setBook(null);
            }
        }

        return $this;
    }

    public function getMetadata(): ?BookMetadata
    {
        return $this->metadata;
    }

    public function setMetadata(?BookMetadata $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
    
    /**
     * Method getCodeByUser
     *
     * @param UserInterface $user [explicite description]
     *
     * @return Code
     */
    public function getCodeByUser(UserInterface $user)
    {
        foreach ($this->codes as $code){
            if($code->getUser() === $user)
                return $code;
        }

        return null;
    }

    public function getInitialDate(): ?\DateTimeInterface
    {
        return $this->initialDate;
    }

    public function setInitialDate(?\DateTimeInterface $initialDate): self
    {
        $this->initialDate = $initialDate;

        return $this;
    }

    public function getModality(): ?string
    {
        return $this->modality;
    }

    public function setModality(string $modality): self
    {
        $this->modality = $modality;

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->setCourse($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getCourse() === $this) {
                $userGroup->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CourseVsit[]
     */
    public function getCourseVsits(): Collection
    {
        return $this->courseVsits;
    }

    public function addCourseVsit(CourseVsit $courseVsit): self
    {
        if (!$this->courseVsits->contains($courseVsit)) {
            $this->courseVsits[] = $courseVsit;
            $courseVsit->setCourse($this);
        }

        return $this;
    }

    public function removeCourseVsit(CourseVsit $courseVsit): self
    {
        if ($this->courseVsits->removeElement($courseVsit)) {
            // set the owning side to null (unless already changed)
            if ($courseVsit->getCourse() === $this) {
                $courseVsit->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Certificate[]
     */
    public function getCertificates(): Collection
    {
        return $this->certificates;
    }

    public function addCertificate(Certificate $certificate): self
    {
        if (!$this->certificates->contains($certificate)) {
            $this->certificates[] = $certificate;
            $certificate->setCourse($this);
        }

        return $this;
    }

    public function removeCertificate(Certificate $certificate): self
    {
        if ($this->certificates->removeElement($certificate)) {
            // set the owning side to null (unless already changed)
            if ($certificate->getCourse() === $this) {
                $certificate->setCourse(null);
            }
        }

        return $this;
    }
    public function getFree(): ?bool
    {
        return $this->free;
    }

    public function setFree(bool $free): self
    {
        $this->free = $free;

        return $this;
    }

   
    /**
     * Undocumented function
     *
     * @param User $user
     * @return UserGroup
     */
    public function findGroupByUser(User $user): UserGroup{
        /** @var UserGroup $userGroup */
        foreach($this->userGroups as $userGroup){
            if ($userGroup->getCreatedBy() === $user){
                return $userGroup;
            }
        }
        return null;
    }

    /**
     * @return Collection|User[]
     */
    public function getFreeUsers(): Collection
    {
        return $this->freeUsers;
    }

    public function addFreeUser(User $freeUser): self
    {
        if (!$this->freeUsers->contains($freeUser)) {
            $this->freeUsers[] = $freeUser;
            $freeUser->addFreeBook($this);
        }

        return $this;
    }

    public function removeFreeUser(User $freeUser): self
    {
        if ($this->freeUsers->removeElement($freeUser)) {
            $freeUser->removeFreeBook($this);
        }

        return $this;
    }


}
