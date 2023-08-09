<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Couverture', 
                VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('Propos',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Contenu',
                ],
                'required' => true
            ])

            ->add('Ad',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Contenu',
                ],
                'required' => true
            ])

            ->add('Banner',ChoiceType::class,[
                'choices' => [
                    "Oui" => true,
                    "Non" => false
                ],
                'required' => true
            ])
            ->add('Tel')
            ->add('Address')
            ->add('Email',EmailType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
