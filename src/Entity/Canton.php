<?php
	
	namespace App\Entity;
	
	use App\Repository\CantonRepository;
	use App\Entity\Provincia;
	use App\Traits\UuidEntityTrait;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Uid\Uuid;
	use Symfony\Component\Validator\Constraints as Assert;
use TimestampableTrait;

/**
	 * @ORM\Entity(repositoryClass=CantonRepository::class)
	 * @ORM\HasLifecycleCallbacks
	 */
	class Canton
	{
		use UuidEntityTrait;
		use TimestampableTrait;
		/**
		 * @ORM\Id()
		 * @ORM\GeneratedValue()
		 * @ORM\Column(type="integer")
		 */
		private $id;
		
		/**
		 * @ORM\Column(type="string", length=255)
		 */
		private $nombre;
		
		/**
		 * @ORM\ManyToOne(targetEntity="App\Entity\Provincia", inversedBy="canton")
		 */
		private $provincia;
		
		/**
		 * @ORM\OneToMany(targetEntity=User::class, mappedBy="canton")
		 */
		private $users;
		
		public function __construct ()
		{
			$this->users = new ArrayCollection();
			$this->uuid = Uuid::v1 ();
		}
		
		public function getId (): ?int
		{
			return $this->id;
		}
		
		public function getNombre (): ?string
		{
			return $this->nombre;
		}
		
		public function setNombre (string $nombre): self
		{
			$this->nombre = $nombre;
			
			return $this;
		}
		
		/**
		 * @return mixed
		 */
		public function getProvincia ()
		{
			return $this->provincia;
		}
		
		/**
		 * @param mixed $provincia
		 */
		public function setProvincia ($provincia): void
		{
			$this->provincia = $provincia;
		}
		
		/**
		 * @return Collection|User[]
		 */
		public function getUsers (): Collection
		{
			return $this->users;
		}
		
		public function addUser (User $user): self
		{
			if (!$this->users->contains ($user)) {
				$this->users[] = $user;
				$user->setCanton ($this);
			}
			
			return $this;
		}
		
		public function removeUser (User $user): self
		{
			if ($this->users->removeElement ($user)) {
				// set the owning side to null (unless already changed)
				if ($user->getCanton () === $this) {
					$user->setCanton (null);
				}
			}
			
			return $this;
		}
		
		public function __toString ()
		{
			return $this->getNombre ();
		}
		
		
	}