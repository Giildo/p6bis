<?php

namespace App\UI\Forms\Trick;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class NewVideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', UrlType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'label'          => false,
                'attr'           => [
                    'placeholder' => 'URL de la vidéo*',
                    'class'       => 'form-control',
                ],
            ]);
    }
}
