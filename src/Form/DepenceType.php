<?php

namespace App\Form;

use App\Entity\Depence;
use App\Entity\Vehicule;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prix')
            ->add('Designation')
            ->add('Date', DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Vehicule', EntityType::class, [
                'class' => Vehicule::class,
                'choice_label' => function($nom){
                    return $nom->getModele()." : ".$nom->getMatricule();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Depence::class,
        ]);
    }
}
