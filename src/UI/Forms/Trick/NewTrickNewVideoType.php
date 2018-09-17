<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\DTO\Trick\NewVideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTrickNewVideoType extends AbstractType
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
                return new NewVideoDTO(
                    $form->get('url')->getData()
                );
            },
        ]);
    }

}
