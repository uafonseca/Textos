<?php

namespace App\Form;

use App\Entity\Activity;
use App\Form\FileUpload\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActivityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];

        $builder
            ->add('name', null, [
                'label' => 'Nombre de la actividad'
            ])
            ->add('page', null, [
                'label' => 'Página para la actividad',
                'help' => 'El conteo de paginas comienza desde 0',
            ]);

        switch ($type) {
            case Activity::TYPE_GENIALLY:
                $builder
                    ->add('url', null, [
                        'label' => 'Url de la actividad Genially'
                    ]);
                break;
            case Activity::TYPE_AUDIO:
                $builder
                    ->add('file', ImageType::class, [
                        'label' => 'Archivo',
                        'attr' => ['class' => 'audio-file'],
                    ]);
                break;
            default:
                $builder
                    ->add('file', ImageType::class, [
                        'label' => 'Archivo',
                        'attr' => ['class' => 'video-file'],
                    ]);
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Activity::class,
        ]);

        $resolver->setRequired('type');
    }
    public function getBlockPrefix()
    {
        return 'activity';
    }
}
