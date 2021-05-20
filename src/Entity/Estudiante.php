<?php

namespace App\Entity;

use App\Repository\EstudianteRepository;
use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use App\Traits\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=EstudianteRepository::class)
 * @ORM\HasLifecycleCallbacks
 */
class Estudiante
{
	use UuidEntityTrait;
    use TimestampableTrait;
    use CompanyEntityTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $brithday;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentorName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentorFirstName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentorLastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentorDNI;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mentorPhone;
    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $scoholOthers;
	
	/**
	 * Estudiante constructor.
	 */
	public function __construct ()
	{
		$this->uuid = Uuid::v1 ();
	}
	
	public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrithday()
    {
        return $this->brithday;
    }

    public function setBrithday( $brithday): self
    {
        $this->brithday = new \DateTime($brithday); 

        return $this;
    }

    public function getMentorName(): ?string
    {
        return $this->mentorName;
    }

    public function setMentorName(string $mentorName): self
    {
        $this->mentorName = $mentorName;

        return $this;
    }

    public function getMentorFirstName(): ?string
    {
        return $this->mentorFirstName;
    }

    public function setMentorFirstName(string $mentorFirstName): self
    {
        $this->mentorFirstName = $mentorFirstName;

        return $this;
    }

    public function getMentorLastName(): ?string
    {
        return $this->mentorLastName;
    }

    public function setMentorLastName(string $mentorLastName): self
    {
        $this->mentorLastName = $mentorLastName;

        return $this;
    }

    public function getMentorDNI(): ?string
    {
        return $this->mentorDNI;
    }

    public function setMentorDNI(string $mentorDNI): self
    {
        $this->mentorDNI = $mentorDNI;

        return $this;
    }

    public function getMentorPhone(): ?string
    {
        return $this->mentorPhone;
    }

    public function setMentorPhone(string $mentorPhone): self
    {
        $this->mentorPhone = $mentorPhone;

        return $this;
    }

    public function getScoholOthers(): ?string
    {
        return $this->scoholOthers;
    }

    public function setScoholOthers(string $scoholOthers): self
    {
        $this->scoholOthers = $scoholOthers;

        return $this;
    }
}
