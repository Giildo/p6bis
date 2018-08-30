<?php

namespace App\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForPasswordDTOInterface;
use App\Domain\DTO\Security\PasswordRecoveryForPasswordDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordRecoveryForPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class, [
            'type'           => PasswordType::class,
            'required'       => false,
            'first_options'  => [
                'attr'           => [
                    'placeholder' => 'Nouveau mot de passe*',
                    'class'       => 'form-control',
                ],
            ],
            'second_options' => [
                'attr'           => [
                    'placeholder' => 'Vérifiez le mot de passe*',
                    'class'       => 'form-control',
                ],
            ],
            'error_bubbling' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordRecoveryForPasswordDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new PasswordRecoveryForPasswordDTO($form->get('password')->getData());
            },
        ]);
    }
}
