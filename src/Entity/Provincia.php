<?php
	
	namespace App\Entity;
	
	use App\Repository\ProvinciaRepository;
	use App\Traits\UuidEntityTrait;
	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Uid\Uuid;
	
	/**
	 * @ORM\Entity(repositoryClass=ProvinciaRepository::class)
	 */
	class Provincia
	{
		use UuidEntityTrait;
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
		 * @ORM\OneToMany(targetEntity="App\Entity\Canton", mappedBy="provincia")
		 */
		private $canton;
		
		/**
		 * @ORM\OneToMany(targetEntity=User::class, mappedBy="provincia")
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
		public function getCanton ()
		{
			return $this->canton;
		}
		
		/**
		 * @param mixed $canton
		 */
		public function setCanton ($canton): void
		{
			$this->canton = $canton;
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
				$user->setProvincia ($this);
			}
			
			return $this;
		}
		
		public function removeUser (User $user): self
		{
			if ($this->users->removeElement ($user)) {
				// set the owning side to null (unless already changed)
				if ($user->getProvincia () === $this) {
					$user->setProvincia (null);
				}
			}
			
			return $this;
		}
		
		public function __toString ()
		{
			return $this->getNombre ();
		}
		
	}