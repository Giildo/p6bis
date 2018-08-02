<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickPictureDTOInterface;
use App\Domain\DTO\Trick\NewTrickPictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTrickPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => 'Description',
            ])
            ->add('picture', FileType::class, [
                'required' => false,
                'label' => 'Image'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewTrickPictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new NewTrickPictureDTO(
                    $form->get('description')->getData(),
                    $form->get('picture')->getData()
                );
            },
        ]);
    }

}
