<?php

namespace App\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;
use App\Domain\DTO\Security\UserRegistrationDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
                'required' => false,
                'label'    => 'Nom d\'utilisateur',
            ])
            ->add('firstName', TextType::class, [
                'required' => false,
                'label'    => 'Prénom',
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
                'label'    => 'Nom',
            ])
            ->add('mail', RepeatedType::class, [
                'type'           => EmailType::class,
                'required'       => false,
                'first_options'  => ['label' => 'Adresse mail'],
                'second_options' => ['label' => 'Vérifiez votre adresse mail'],
            ])
            ->add('password', RepeatedType::class, [
                'type'           => PasswordType::class,
                'required'       => false,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Vérifiez votre mot de passe'],
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
