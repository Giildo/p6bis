<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use App\Domain\DTO\Trick\NewTrickVideoDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTrickVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, [
                'required' => false,
                'label'    => 'URL de la vidéo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewTrickVideoDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new NewTrickVideoDTO(
                    $form->get('url')->getData()
                );
            },
        ]);
    }

}
