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
                'label' => 'Detalles',
                'required' => true,
            ])
            ->add('value',null,[
                'label' => 'Valor',
                'required' => true,
            ])
            ->add('iva',null,[
                'label' => 'Impuesto IVA(%)',
                'required' => true,
                'attr' => ['placeholder' => 'Ej. 12']
            ])
            ->add('currency',null,[
                'label' => 'Moneda',
                'required' => true,
            ])
            ->add('total',null,[
                'label' => 'Total',
                'required' => true,
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
