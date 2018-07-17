<?php

namespace App\UI\Forms\Security;

use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\Domain\DTO\Security\UserConnectionDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserConnectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label'    => 'Nom d\'utilisateur',
                'required' => false,
            ])
            ->add('password', PasswordType::class, [
                'label'    => 'Mot de passe',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserConnectionDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new UserConnectionDTO(
                    $form->get('username')->getData(),
                    $form->get('password')->getData()
                );
            },
        ]);
    }


}
