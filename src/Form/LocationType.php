<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('name', TextType::class, ['label'=>'Nom du lieu'])
            ->add('streetAddress', TextType::class, ['label'=>'Adresse'])
            ->add('latitude', NumberType::class)
            ->add('longitude', NumberType::class)
            ->add('city', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'required' => false,
                'class' => City::class,
                'label'=> 'Lieu',
                'choice_label' => 'name'
            ])
//            ->add('save', SubmitType::class, [
//                'attr' => [
//                    'class' => 'bottom-button',
//                    'id' => 'newLocation'
//                    ]
//            ])
//            ->add('annuler', ResetType::class, [
//                'label' => 'Annuler',
//                'attr' => ['class' => 'bottom-button']
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
