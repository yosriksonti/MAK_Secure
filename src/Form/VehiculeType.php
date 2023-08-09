<?php

namespace App\Form;

use App\Entity\Park;
use App\Entity\Vehicule;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('Marque')
            ->add('Modele')
            ->add('Categorie')
            ->add('Boite', ChoiceType::class,[
                'choices' => [
                    'Automatique' => 'Automatique',
                    'Manuelle' => 'Manuelle'
                ]
            ])
            ->add('Carb', ChoiceType::class,[
                'choices' => [
                    'Essence' => 'Essence',
                    'Gasoil' => 'Gasoil'
                ]
            ])
            ->add('Nb_Places')
            ->add('Nb_Portes')
            ->add('Nb_Val')
            ->add('Caut', NumberType::class)
            ->add('Prix', NumberType::class)
            ->add('PrixHS', NumberType::class)
            ->add('Reservoire', NumberType::class)
            ->add('Clim')
            ->add('Dispo',ChoiceType::class,[
                'choices' => [
                    "Oui" => true,
                    "Non" => false
                ],
                'required' => true
            ])
            ->add('isUnlimitedMileage')
            ->add('isCarInsurance')
            ->add('isPassengerInsurance')
            ->add('isVAT')
            ->add('Matricule')
            ->add('Park', EntityType::class, [
                'class' => Park::class,
                'choice_label' => function($nom){
                    return $nom->getNom();
                },
            ])
            ->add('Description')
            ->add('Description_Det')
            ->add('Grise', 
                VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('Def', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('Reel', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('Saison', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
