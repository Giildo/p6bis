<?php

namespace App\UI\Forms\User;

use App\Domain\DTO\Interfaces\User\ProfilePictureDTOInterface;
use App\Domain\DTO\User\ProfilePictureDTO;
use App\Domain\Model\Picture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilePictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
                'attr'           => [
                    'placeholder' => 'Prénom*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('lastName', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
                'attr'           => [
                    'placeholder' => 'Nom*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('mail', EmailType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
                'attr'           => [
                    'placeholder' => 'Adresse mail*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('profilePicture', PictureType::class, [
                'required' => false,
                'label'    => 'Image d\'en-tête',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfilePictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new ProfilePictureDTO(
                    $form->get('firstName')->getData(),
                    $form->get('lastName')->getData(),
                    $form->get('mail')->getData(),
                    $form->get('profilePicture')->getData()
                );
            },
        ]);
    }

}
