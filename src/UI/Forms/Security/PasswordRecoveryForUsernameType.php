<?php

namespace App\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForUsernameDTOInterface;
use App\Domain\DTO\Security\PasswordRecoveryForUsernameDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordRecoveryForUsernameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, [
            'required' => false,
            'label'    => 'Nom d\'utilisateur',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PasswordRecoveryForUsernameDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new PasswordRecoveryForUsernameDTO($form->get('username')->getData());
            },
        ]);
    }
}
