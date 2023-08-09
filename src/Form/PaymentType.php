<?php

namespace App\Form;

use App\Entity\Payment;
use App\Entity\Client;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sessionId')
            ->add('status',ChoiceType::class,[
                'choices' => [
                    'pending' => 'pending',
                    'paid' => 'paid',
                    'cancelled' => 'cancelled',
                    'refused' => 'refused',
                ]
            ])
            ->add('total')
            ->add('createdOn',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => function($cin){
                    return $cin->getCIN();
                },
            ])
            ->add('Location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => function($num){
                    return $num->getNum();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
