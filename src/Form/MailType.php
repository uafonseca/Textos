<?php

namespace App\Form;

use App\Entity\Mail;
use App\Form\FileUpload\ImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject',null,[
                'label' => 'Asunto'
            ])
            // ->add('recipients',null,[
            //     'label' => 'Destinatarios'
            // ])
            ->add('context', null,[
                'label' => 'Contenido',
                // 'config' => ['toolbar' => 'basic'],
            ])
            ->add('homework', null,[
                'label' => 'Tarea',
                // 'config' => ['toolbar' => 'basic'],
            ])
            
            ->add('attached',ImageType::class,[
                'label' => 'Adjunto',
                // 'help' => 'Dimensiones de 312 x 232 píxeles',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Mail::class,
        ]);
    }
}
