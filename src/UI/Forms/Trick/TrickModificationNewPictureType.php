<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Trick\PictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickModificationNewPictureType extends AbstractType
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
                return new PictureDTO(
                    $form->get('description')->getData(),
                    $form->get('picture')->getData()
                );
            },
        ]);
    }
}
