<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 15/02/2021
 * Time: 7:38 PM
 */

namespace App\Entity;

use App\Traits\CompanyEntityTrait;
use App\Traits\UuidEntityTrait;
use Symfony\Component\Uid\Uuid;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Traits\TimestampableTrait;

/**
 * @Vich\Uploadable
 * @ORM\Entity
 * @ORM\Table(name="`pdf`")
 * @Assert\Callback(methods={"validate"})
 * @ORM\HasLifecycleCallbacks
 */
class PDF
{
	use UuidEntityTrait;
	use TimestampableTrait;
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
	 * @Vich\UploadableField(mapping="pdf_file", fileNameProperty="pdfName", originalName="originalName", mimeType="mimeType", size="size")
	 *
	 * @var File
	 */
	protected $pdfFile;

	/**
	 * @var string
	 *
	 *
	 * @ORM\Column(type="string", length=255, name="doc_name", nullable = true)
	 */
	protected $pdfName;

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
	public function __construct()
	{
		$this->uuid = Uuid::v1();
	}

	 /**
     * @param ExecutionContextInterface $context
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (! in_array($this->pdfFile->getMimeType(), array(
            'application/pdf',
        ))) {
            $context
                ->buildViolation('Solo se aceptan archivos de tipo PDF')
                ->atPath('pdfName')
                ->addViolation()
            ;
        }
    }


	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getOriginalName()
	{
		return $this->originalName;
	}

	/**
	 * @param string $originalName
	 */
	public function setOriginalName($originalName): void
	{
		$this->originalName = $originalName;
	}


	/**
	 * @param \Symfony\Component\HttpFoundation\File\File|null $file
	 *
	 * @throws \Exception
	 */
	public function setPdfFile(\Symfony\Component\HttpFoundation\File\File $file = null)
	{
		$this->pdfFile = $file;

		if ($file) {
			// It is required that at least one field changes if you are using doctrine
			// otherwise the event listeners won't be called and the file is lost
			$this->updateAt = new \DateTime('now');
		}
	}

	/**
	 * @return File
	 */
	public function getPdfFile()
	{
		return $this->pdfFile;
	}

	/**
	 * @param string $pdfName
		
	 */
	public function setPdfName($pdfName)
	{
		$this->pdfName = $pdfName;
	}

	/**
	 * @return string
	 */
	public function getPdfName()
	{
		return $this->pdfName;
	}

	/**
	 * Set updateAt.
	 *
	 * @param \DateTime $updateAt
	 *
	 * @return File
	 */
	public function setUpdateAt($updateAt)
	{
		$this->updateAt = $updateAt;

		return $this;
	}

	/**
	 * Get updateAt.
	 *
	 * @return \DateTime
	 */
	public function getUpdateAt()
	{
		return $this->updateAt;
	}

	public function __toString()
	{
		return $this->getOriginalName() . '';
	}

	/**
	 * @return string
	 */
	public function getMimeType(): ?string
	{
		return $this->mimeType;
	}

	/**
	 * @param string $mimeType
	 *
	 * @return File
	 */
	public function setMimeType(?string $mimeType)
	{
		$this->mimeType = $mimeType;
		//			return $this;
	}

	/**
	 * @return string
	 */
	public function getSize(): ?string
	{
		return $this->size;
	}

	/**
	 * @param string $size
	 *
	 * @return File
	 */
	public function setSize(?string $size)
	{
		$this->size = $size;
		return $this;
	}
}
