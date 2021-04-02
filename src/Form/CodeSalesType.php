<?php

namespace App\Form;

use App\Entity\CodeSalesData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CodeSalesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('details',null,[
                'label' => 'Detalles'
            ])
            ->add('value',null,[
                'label' => 'Valor'
            ])
            ->add('iva',null,[
                'label' => 'Impuesto IVA'
            ])
            ->add('currency',null,[
                'label' => 'Moneda'
            ])
            ->add('total',null,[
                'label' => 'Total'
            ])
            ->add('financialDetails', FinancialDetailsType::class,[
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CodeSalesData::class,
        ]);
    }
}
