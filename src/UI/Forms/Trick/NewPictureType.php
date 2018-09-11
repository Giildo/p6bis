<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\Domain\DTO\Trick\NewTrickNewPictureDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPictureType extends AbstractType
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
            'data_class' => TrickNewPictureDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new NewTrickNewPictureDTO(
                    $form->get('description')->getData(),
                    $form->get('picture')->getData()
                );
            }
        ]);
    }
}
