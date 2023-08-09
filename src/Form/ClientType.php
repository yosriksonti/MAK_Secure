<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('name')
            ->add('lastname')
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'form-control password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Repeat Password'],
            ])
            ->add('Pays', TextType::class, [
                'required' => false,
                'empty_data' => ' '
            ]) 
            ->add('Telephone')
            ->add('Add1')
            ->add('Add2', TextType::class, [
                'required' => false,
                'empty_data' => " "
            ])
            ->add('Permis', TextType::class, [
                'required' => false,
            ])
            ->add('Date_Permis',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('CIN', TextType::class, [
            ])
            ->add('Date_CIN',DateType::class, [ 
                'widget' => 'single_text',
                ])
            ->add('Date_Naissance',DateType::class, [ 
                'widget' => 'single_text',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
