<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\Domain\DTO\Trick\TrickModificationNewPictureDTO;
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
            'data_class' => TrickNewPictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new TrickModificationNewPictureDTO(
                    $form->get('description')->getData(),
                    $form->get('picture')->getData()
                );
            },
        ]);
    }
}
