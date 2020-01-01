<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Trick\NewPictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTrickNewPictureType extends AbstractType
{
    public function getParent()
    {
        return NewPictureType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new NewPictureDTO(
                    $form->get('description')->getData(),
                    $form->get('picture')->getData()
                );
            },
        ]);
    }

}
