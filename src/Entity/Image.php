<?php
	/**
	 * Created by PhpStorm.
	 * User: Ubel
	 * Date: 15/02/2021
	 * Time: 7:38 PM
	 */
	
	namespace App\Entity;

use App\Traits\CompanyEntityTrait;
use Symfony\Component\Uid\Uuid;
	use Vich\UploaderBundle\Mapping\Annotation as Vich;
	use Doctrine\ORM\Mapping as ORM;
	
	
	/**
	 * @Vich\Uploadable
	 * @ORM\Entity
	 * @ORM\Table(name="`image`")
	 * @ORM\HasLifecycleCallbacks
	 */
	class Image
	{
		use CompanyEntityTrait;
		/**
		 * @var int
		 *
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\Id
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		private $id;
		
		/**
		 * NOTE: This is not a mapped field of entity metadata, just a simple property.
		 *
		 * @Vich\UploadableField(mapping="imagen_file", fileNameProperty="imagenName", originalName="originalName", mimeType="mimeType", size="size")
		 *
		 * @var File
		 */
		protected $imagenFile;
		
		/**
		 * @var string
		 *
		 *
		 * @ORM\Column(type="string", length=255, name="doc_name", nullable = true)
		 */
		protected $imagenName;
		
		/**
		 * @var string
		 *
		 *
		 * @ORM\Column(type="string", length=255, name="mimeType", nullable = true)
		 */
		protected $mimeType;
		
		
		/**
		 * @var string
		 *
		 *
		 * @ORM\Column(type="string", length=255, name="size", nullable = true)
		 */
		protected $size;
		/**
		 * @var string
		 *
		 *
		 * @ORM\Column(type="string", length=255, name="original_name", nullable = true)
		 */
		protected $originalName;
		
		/**
		 * @var \DateTime
		 *
		 *
		 * @ORM\Column(type="datetime", nullable = true)
		 */
		protected $updateAt;
		
		
		/**
		 * Image constructor.
		 */
		public function __construct ()
		{
			$this->uuid = Uuid::v1 ();
		}
		
		
		/**
		 * @return int
		 */
		public function getId (): int
		{
			return $this->id;
		}
		
		/**
		 * @param int $id
		 */
		public function setId (int $id): void
		{
			$this->id = $id;
		}
		
		/**
		 * @return string
		 */
		public function getOriginalName ()
		{
			return $this->originalName;
		}
		
		/**
		 * @param string $originalName
		 */
		public function setOriginalName ($originalName): void
		{
			$this->originalName = $originalName;
		}
		
		
		/**
		 * @param \Symfony\Component\HttpFoundation\File\File|null $file
		 *
		 * @throws \Exception
		 */
		public function setImagenFile (\Symfony\Component\HttpFoundation\File\File $file = null)
		{
			$this->imagenFile = $file;
			
			if ($file) {
				// It is required that at least one field changes if you are using doctrine
				// otherwise the event listeners won't be called and the file is lost
				$this->updateAt = new \DateTime('now');
			}
		}
		
		/**
		 * @return File
		 */
		public function getImagenFile ()
		{
			return $this->imagenFile;
		}
		
		/**
		 * @param string $imagenName
		 */
		public function setImagenName ($imagenName)
		{
			$this->imagenName = $imagenName;
		}
		
		/**
		 * @return string
		 */
		public function getImagenName ()
		{
			return $this->imagenName;
		}
		
		/**
		 * Set updateAt.
		 *
		 * @param \DateTime $updateAt
		 *
		 * @return File
		 */
		public function setUpdateAt ($updateAt)
		{
			$this->updateAt = $updateAt;
			
			return $this;
		}
		
		/**
		 * Get updateAt.
		 *
		 * @return \DateTime
		 */
		public function getUpdateAt ()
		{
			return $this->updateAt;
		}
		
		public function __toString ()
		{
			return $this->getOriginalName () . '';
		}
		
		/**
		 * @return string
		 */
		public function getMimeType (): ?string
		{
			return $this->mimeType;
		}
		
		/**
		 * @param string $mimeType
		 *
		 * @return File
		 */
		public function setMimeType (?string $mimeType)
		{
			$this->mimeType = $mimeType;
//			return $this;
		}
		
		/**
		 * @return string
		 */
		public function getSize (): ?string
		{
			return $this->size;
		}
		
		/**
		 * @param string $size
		 *
		 * @return File
		 */
		public function setSize (?string $size)
		{
			$this->size = $size;
			return $this;
		}
		
	}