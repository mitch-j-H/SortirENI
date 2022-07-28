<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\Location;
use App\Form\model\EventFormModel;
use App\Repository\CityRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
    //make cosntructor to pass in locationRepository
    private CityRepository $cityRepository;
    private LocationRepository $locationRepository;

    public function __construct(LocationRepository $locationRepository, CityRepository $cityRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->cityRepository = $cityRepository;
    }

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
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
//                'query_builder' => function (EntityRepository $repo){
//                return $repo->createQueryBuilder('c')
//                    ->orderBy(c.name, 'ASC');
//                }
            ])

            ->add('Location', EntityType::class, [
                'placeholder' => 'Choisir une ville',
                'required' => false,
                //this is just temp testing of dynamic form
                'class' => Location::class,
                'label'=> 'Lieu',
                'choice_label' => 'name'
//                'query_builder' => function (EntityRepository $repo){
//                    return $repo->createQueryBuilder('l')
//                        ->orderBy('l.name', 'ASC');
//                }
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
//            ->add('addLocale', SubmitType::class, [
//                'label' => 'Ajouter Lieu',
//                'attr' => [
//                    'class' => 'addCity',
////                    'href' => '{{path ('')}}'
//                    ],
//            ])
;
//            ->add('NewLocation', CollectionType::class, [
//                'entry_type' => LocationType::class,
//                'entry_options' => ['label' => false],
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false
////                'prototype' => true,
////                'prototype_data' => 'New Tag Placeholder'
//            ])
            ;

            //adding event listeners
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetData'));
//        $builder->addEventListener(FormEvents::PRE_SUBMIT, array($this, 'onPreSubmit'));
    }

//    protected function addElementLocation(FormInterface $form, City $city = null){
//        //adding ville element
//        $form->add('City', EntityType::class, [
//                'class'=>City::class,
//                'choices'=>$city,
//                'choice_label'=>'name',
//                'placeholder'=>'Choisir une ville',
//                'label'=>'Ville'
//            ]);
//
//        //locations empty unless there is a city selected
//        $locations = array();
//
//        //if city selected find associated locations
//        if($city)
//        {
//            $locations = $this->locationRepository->findAllByCity($city);
//        }
//
//        //add the locations to the select
//        $form->add('Locations', EntityType::class, [
//           'class' => Location::class,
//           'choices'=>$locations,
//            'choice_label'=>'name',
//            'placeholder'=>'Choisir un lieu',
//            'label'=>'Lieu'
//        ]);
//    }
//error running code on
//    function onPreSubmit(FormEvent $event) {
//        $form = $event->getForm();
//        $data = $event->getData();
//
//        // Search for selected City and convert it into an Entity
//        $city = $this->cityRepository->find($data);
//
//        $form->add('city', EntityType::class,[
//            'class'=>City::class,
//            'choices'=>$city,
//            'choice_label'=>'name',
//            'placeholder'=>'Choisir une ville',
//            'label'=>'Ville'
//        ]);
//    }

    //Temp removing this function to facilitate writting
//    function onPreSetData(FormEvent $event) {
//        $person = $event->getData();
//        $form = $event->getForm();
//
//        // When you create a new event, the City is always empty
//        $city = $person->getLocation() ? $person->getLocation() : null;
//
//        $this->addElementLocation($form, $city);
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
//            'data_class' => EventFormModel::class,
        ]);
    }

}

/////////////////
/// this is just a temporary holding position
////////////////
//         $formModifier = function (FormInterface $form, City $city = null){
//            $locations = null === $city ? [] : $city->getLocations();
//
//
//            //add to the form
//            $form->add('Location', EntityType::class, [
//                'class'=>Location::class,
//                'choices'=>$locations,
//                'choice_label'=>'name',
//                'placeholder'=>'Choisir un lieu',
//                'label'=>'Lieu'
//            ]);
//        };
//
//        $builder->get('city')->addEventListener(
//          FormEvents::POST_SUBMIT,
//            function (FormEvent $event) use ($formModifier){
//              $city = $event->getForm()->getData();
//              $formModifier($event->getForm()->getParent(), $city);
//            }
//        );
