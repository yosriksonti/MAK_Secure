<?php

namespace App\Form;

use App\Entity\Park;
use App\Entity\Auto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AutoType extends AbstractType
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
            ->add('Prix', NumberType::class)
            ->add('Kilos', NumberType::class)
            ->add('Clim')
            ->add('isVAT')
            ->add('Matricule')
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
            ->add('image', PictureType::class,[
                'mapped' => false,
                'required' => false
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'data_class' => Auto::class,
        ]);
    }
}
