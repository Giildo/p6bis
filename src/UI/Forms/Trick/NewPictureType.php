<?php

namespace App\UI\Forms\Trick;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class NewPictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
                'attr'           => [
                    'placeholder' => 'Description de l\'image*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('picture', FileType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
            ]);
    }
}
