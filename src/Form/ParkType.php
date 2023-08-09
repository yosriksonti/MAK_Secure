<?php

namespace App\Form;

use App\Entity\Park;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('DebutHS',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('FinHS',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('DebutBS',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('FinBS',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('PrixBabySeat')
            ->add('PrixPersonalDriver')
            ->add('PrixSecondDriver')
            ->add('PrixSTW')


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Park::class,
        ]);
    }
}
