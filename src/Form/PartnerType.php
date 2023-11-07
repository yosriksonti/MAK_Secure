<?php

namespace App\Form;

use App\Entity\Park;
use App\Entity\Partner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder
            ->add('Nom')
            ->add('Def', VichImageType::class, [
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
            'data_class' => Partner::class,
        ]);
    }
}
