<?php

namespace App\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;
use App\Domain\DTO\Security\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => 'Nom d\'utilisateur*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('firstName', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => 'Prénom*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('lastName', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => 'Nom*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('mail', RepeatedType::class, [
                'type'           => EmailType::class,
                'required'       => false,
                'first_options'  => [
                    'attr' => [
                        'placeholder' => 'Adresse mail*',
                        'class'       => 'form-control',
                        ],
                    ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Confirmer l\'adresse mail*',
                        'class'       => 'form-control',
                        ],
                    ],
                'error_bubbling' => true,
            ])
            ->add('password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'required'       => false,
                'first_options'  => [
                    'attr' => [
                        'placeholder' => 'Mot de passe*',
                        'class'       => 'form-control',
                        ],
                    ],
                'second_options' => [
                    'attr' => [
                        'placeholder' => 'Confirmer le mot de passe*',
                        'class'       => 'form-control',
                        ],
                    ],
                'error_bubbling' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserRegistrationDTO(
                    $form->get('username')->getData(),
                    $form->get('firstName')->getData(),
                    $form->get('lastName')->getData(),
                    $form->get('mail')->getData(),
                    $form->get('password')->getData()
                );
            },
        ]);
    }
}
