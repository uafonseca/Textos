<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\MailResponse;
use App\Form\FileUpload\ImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('context',CKEditorType::class,[
                'config' => ['toolbar' => 'basic'],
                'label' => 'Contenido de la respuesta',
                'required' => true
            ])

            ->add('attached', ImageType::class,[
                'label' => 'Adjunto',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MailResponse::class,
        ]);
    }
}
