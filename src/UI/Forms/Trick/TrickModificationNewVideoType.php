<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewVideoDTOInterface;
use App\Domain\DTO\Trick\TrickModificationNewVideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickModificationNewVideoType extends AbstractType
{
    public function getParent()
    {
        return NewVideoType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickNewVideoDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new TrickModificationNewVideoDTO(
                    $form->get('url')->getData()
                );
            },
        ]);
    }
}
