<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use App\Form\model\EventFormModel;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventType extends AbstractType
{
//    public function buildForm(FormBuilderInterface $builder, array $options): void
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :',
                'attr'=>['placeholder'=>'Nom de la sortie']
                ])

            ->add('startsAt', DateTimeType::class, [
                'html5'=>true,
                'widget'=> 'single_text',
                'label'=>'Date et heure de la sortie :',
                'input_format' => 'dd:MM:YYYY hh:mm'
            ])
            ->add('cutOffDate', DateType::class, ['html5'=>true, 'widget'=> 'single_text', 'label'=>'Date limite d inscription :'])
            ->add('duration', IntegerType::class, ['label'=>'Durée :'])
            ->add('capacity', NumberType::class, [
                'required'=> true,
                'label'=>'Nombre de places :',
                'attr'=> [ 'min'=>1, 'max'=>50],
                'attr'=>['placeholder'=>'Nombre de places'],
                ])
            ->add('eventInfo', TextareaType::class, [
                'label'=>'Description et infos :',
                'attr'=>['placeholder'=>'Description et info']
            ])

            ->add('city', EntityType::class, [
                'class' => City::class,
                'mapped' => false,
                'label'=> 'Ville',
                'choice_label' => 'name',
//                'query_builder' => function (EntityRepository $repo){
//                return $repo->createQueryBuilder('v')
//                    ->orderBy(v.name, 'ASC');
//                }
                ])
            ->add('latitude', NumberType::class, [
                'mapped' => false,
                'label'=> 'Latitude',
                'required'=>false,
                'attr'=>['placeholder'=>'Latitude']
            ])
            ->add('longitude', NumberType::class, [
                'mapped' => false,
                'label'=> 'Longitude',
                'required'=>false,
                'attr'=>['placeholder'=>'Longitude']
            ])

            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'bottom-button']
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => ['class' => 'bottom-button']
            ])
            ->add('annuler', ResetType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'bottom-button']
            ])
            ->add('addLocale', SubmitType::class, [
                'label' => 'Ajouter Lieu',
                'attr' => ['class' => 'addCity']
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
//                'query_builder' => function (EntityRepository $repo){
//                return $repo->createQueryBuilder('c')
//                    ->orderBy(c.name, 'ASC');
//                }
            ])

            ->add('Location', ChoiceType::class, [
                'placeholder' => 'Choisir une ville',
                'required' => false
                //this is just temp testing of dynamic form
//                'class' => Location::class,
//                'label'=> 'Lieu',
//                'choice_label' => 'name',
//                'query_builder' => function (EntityRepository $repo){
//                    return $repo->createQueryBuilder('l')
//                        ->orderBy('l.name', 'ASC');
//                }
            ]);

        $formModifier = function (FormInterface $form, City $city = null){
            $loctions = null === $city ? [] : $city->getLocations();


            //add to the form
            $form->add('Location', EntityType::class, [
                'class'=>Location::class,
                'choices'=>$loctions,
                'choice_label'=>'name',
                'placeholder'=>'Choisir un lieu',
                'label'=>'Lieu'
            ]);
        };

        $builder->get('city')->addEventListener(
          FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier){
              $city = $event->getForm()->getData();
              $formModifier($event->getForm()->getParent(), $city);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
//            'data_class' => EventFormModel::class,
        ]);
    }

}
