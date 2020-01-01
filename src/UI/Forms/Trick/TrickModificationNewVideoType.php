<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\DTO\Trick\VideoDTO;
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
            'data_class' => VideoDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new VideoDTO(
                    $form->get('url')->getData()
                );
            },
        ]);
    }
}
