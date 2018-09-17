<?php

namespace App\UI\Forms\User;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Trick\PictureDTO;
use App\UI\Forms\Trick\NewPictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AbstractType
{
    public function getParent()
    {
        return NewPictureType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->remove('description');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new PictureDTO(
                    'Photo de profil',
                    $form->get('picture')->getData()
                );
            },
        ]);
    }
}
