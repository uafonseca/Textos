<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\HtmlCode;
use App\Entity\Level;
use App\Entity\Link;
use App\Entity\SchoolStage;
use App\Form\FileUpload\ImageType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            ->add('title',TextType::class,[
                'label' => 'Título'
            ])
            ->add('source',ChoiceType::class,[
                'label' => 'Destinado a',
                'choices' => [
                    'Estudiante' => 'Estudiante',
                    'Docente' => 'Docente',
                ],
                'multiple' => true,
            ])
            ->add('category', EntityType::class,[
                'class'=> Category::class,
                'query_builder' => function (EntityRepository $repository){
                    return $repository->createQueryBuilder('e');
                },
                'label' => 'Categoría'
            ])
            ->add('stage', EntityType::class,[
                'label' => 'Grupo',
                'class'=> SchoolStage::class,
            ])
            ->add('level', EntityType::class,[
                'label' => 'Tipo de curso',
                'class'=> Level::class,
            ])
            ->add('portada',ImageType::class,[
                'label' => 'Portada',
                'required' => !$options['edit'],
                'help' => 'Dimensiones de 312 x 232 píxeles',
            ])
            ->add('banner',ImageType::class,[
                'label' => 'Baner del curso',
                'required' => !$options['edit'],
                'help' => 'Dimensiones de 1180 x 350 píxeles',
            ])
            ->add('metadata',BookMetadataType::class,[
                'label' => false,
            ])
            ->add('free', CheckboxType::class,[
                'label' => 'Gratuito'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
        $resolver->setRequired('edit');
    }
}
