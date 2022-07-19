<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {



        $builder
            ->add('name', TextType::class, ['label'=>'Nom de la sortie :'])

            ->add('startsAt', DateType::class, ['html5'=>true, 'widget'=> 'single_text', 'label'=>'Date et heure de la sortie :'])
            ->add('cutOffDate', DateType::class, ['html5'=>true, 'widget'=> 'single_text', 'label'=>'Date limite d inscription :'])
            ->add('duration', IntegerType::class, ['label'=>'Durée :'])
            ->add('capacity', IntegerType::class, ['label'=>'Nombre de places :'])
            ->add('eventInfo', TextareaType::class, ['label'=>'Description et infos :'])

            // A changer , car campus = celui de l'organisateur

            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label'=> 'name',
                'query_builder' => function (EntityRepository $repo){
                return $repo->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
            }

            ])
            ->add('Location', TextType::class)



        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }

}
