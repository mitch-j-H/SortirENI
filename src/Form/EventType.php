<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :',
                'data'=>'Nom de la sortie'
                ])

            ->add('startsAt', DateTimeType::class, [
                'html5'=>true,
                'widget'=> 'single_text',
                'label'=>'Date et heure de la sortie :',
                'input_format' => 'dd:MM:YYYY hh:mm'
            ])
            ->add('cutOffDate', DateType::class, ['html5'=>true, 'widget'=> 'single_text', 'label'=>'Date limite d inscription :'])
            ->add('duration', IntegerType::class, ['label'=>'Durée :'])
            ->add('capacity', RangeType::class, [
                'required'=> true,
                'label'=>'Nombre de places :',
                'attr'=> [ 'min'=>1, 'max'=>50],
                'data'=>'10',
                ])
            ->add('eventInfo', TextareaType::class, ['label'=>'Description et infos :'])



            ->add('Location', Location::class/* [
                'class' => Location::class,
                'label'=> 'Lieu',
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $repo){
                return $repo->createQueryBuilder('l')
                    ->orderBy('l.name', 'ASC');

                }
            ]*/)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}
