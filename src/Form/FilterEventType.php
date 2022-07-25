<?php

    namespace App\Form;


    use App\Entity\Campus;
    use Doctrine\ORM\EntityRepository;
    use Symfony\Bridge\Doctrine\Form\Type\EntityType;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
    use Symfony\Component\Form\Extension\Core\Type\DateType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use App\Form\DTO\EventDTO;

    class FilterEventType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                //Barre de recherche
                ->add('name', TextType::class, [
                    'label' => 'nom de la sortie',
                    'required' => false,
                    'empty_data' => '',
                    'attr' => ['placeholder' => 'Rechercher']
                ])
                ->add('campus', EntityType::class, [
                    'required' => false,
                    'class' => Campus::class,
                    'label' => 'Campus',
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $repo) {
                        return $repo->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                ])
                ->add('isTheOrganiser', CheckboxType::class, [
                    'label' => 'Sorties dont je suis l\'organisateur',
                    'required' => false,
                ])
                ->add('eventAttendenceTrue', CheckboxType::class, [
                    'label' => 'Sorties auxquelles je suis inscrit',
                    'required' => false,
                ])
                ->add('eventAttendenceFalse', CheckboxType::class, [
                    'label' => 'Sorties auxquelles je ne suis pas inscrit',
                    'required' => false,
                ])
                ->add('pastEvent', CheckboxType::class, [
                    'label' => 'Sorties passées',
                    'required' => false,
                ])
                ->add('fromDate', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                    'label' => 'Entre le ',
                ])
                ->add('toDate', DateType::class, [
                    'widget' => 'single_text',
                    'required' => false,
                    'label' => 'et le ',
                ]);
        }


        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => EventDTO::class
            ]);
        }
    }