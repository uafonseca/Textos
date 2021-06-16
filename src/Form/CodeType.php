<?php

namespace App\Form;

use App\Entity\Book;
use App\Entity\Code;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('book', EntityType::class, [
            'label' => 'libro',
            'class' => Book::class,
            'query_builder' => function (EntityRepository $repository) {
                return $repository->createQueryBuilder('book');
            },
            'choice_label' => 'title',
            'placeholder' => '--SELECCIONE--',
            'required' => true
        ])
        ->add('starDate',DateTimePickerType::class,[
            'label' => 'Fecha inicial',
            'html5' => false,
            'placeholder' => 'dd-mm-yyyy',
            'format' => 'dd-MM-yyyy',
            'widget' => 'single_text',
            'required' => true,
            'input' => 'datetime_immutable',
        ])
        ->add('totalDays', NumberType::class, ['label' => 'Días de activación', 'required' => true, 'mapped' => false,])
        ->add('total', NumberType::class,[
            'label' => 'Cantidad de códigos a generar',
             'required' => true,
             'mapped' => false,
         ])
        ->add('unlimited', CheckboxType::class, ['label' => 'Activación ilimitada', 'required' => false])
        ->add('salesData',CodeSalesType::class, ['label' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Code::class,
        ]);
    }
}
