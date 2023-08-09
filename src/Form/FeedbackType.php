<?php

namespace App\Form;

use App\Entity\Feedback;
use App\Entity\Client;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Body')
            ->add('createdOn',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Rating')
            ->add('Client',EntityType::class, [
                'class' => Client::class,
                'choice_label' => function($cin){
                    return $cin->getCIN();
                },
            ])
            ->add('Vehicule',EntityType::class, [
                'class' => Vehicule::class,
                'placeholder' => '',
                'required' => false,
                'empty_data' => '',
                'choice_label' => function($cin){
                    return $cin->getMatricule();
                }
            ])
            ->add('Visible',ChoiceType::class,[
                'choices' => [
                    'Oui' => '1',
                    'Non' => '0'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
