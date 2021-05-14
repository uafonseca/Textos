<?php

namespace App\Form;

use App\Entity\SocialNetworks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facebook',TextType::class,[
                'label' => 'Facebook',
                'attr' => ['placeholder' =>'https://www.facebook.com/[username]']
            ])
            ->add('twitter',TextType::class,[
                'label' => 'Twitter',
                'attr' => ['placeholder' =>'https://www.twitter.com/[username]']
            ])
            ->add('instagram',TextType::class,[
                'label' => 'Instagram',
                'attr' => ['placeholder' =>'https://www.instagram.com/[username]']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SocialNetworks::class,
        ]);
    }
}
