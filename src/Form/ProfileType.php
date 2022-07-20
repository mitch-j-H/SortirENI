<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder

            ->add('userName', TextType::class, [
                'label' => 'UserName',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a user name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your user name should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('surname', TextType::class, [
                'label' => 'Surname',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a surname',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your surname should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'First name',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a first name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your first name should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 50,
                    ]),
                ]
            ])
            ->add('telephone',TextType::class, [
                'label' => 'Telephone'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email'
            ])
            /*->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'passwords do not match',
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm your password']
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => 'Image file',
                'constraints' => [
                    new file([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image'
                    ])
                ]
            ])*/
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $repository) {
                return $repository->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ])
            ->add('reset', ResetType::class, [
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
