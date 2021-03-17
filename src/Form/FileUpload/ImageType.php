<?php

/**
 * Created by PhpStorm.
 * User: Ubel
 * Date: 15/02/2021
 * Time: 8:05 PM
 */

namespace App\Form\FileUpload;


use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ImageType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('imagenFile', VichFileType::class, [
				'attr' => $options['attr'] ? $options['attr'] : ['class' => 'file_to_upload'],
				'label' => false,
				'download_uri' => true,
				'allow_delete' => false,
			]);
	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' =>  Image::class,
		]);
	}
}
