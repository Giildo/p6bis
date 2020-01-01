<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickModificationDTOInterface;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => 'Description*',
                    'class'       => 'form-control',
                ],
            ])
            ->add('published', CheckboxType::class, [
                'required'       => false,
                'label'          => 'Publier',
                'error_bubbling' => true,
                'attr'           => [
                    'class' => 'form-control checkbox',
                ],
            ])
            ->add('category', EntityType::class, [
                'required'     => false,
                'class'        => Category::class,
                'choice_label' => 'name',
                'label'        => 'Catégorie*',
                'placeholder'  => null,
            ])
            ->add('newPictures', CollectionType::class, [
                'label'         => 'Nouvelles photos',
                'entry_type'    => TrickModificationNewPictureType::class,
                'allow_add'     => true,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('newVideos', CollectionType::class, [
                'label'         => 'Nouvelles videos',
                'entry_type'    => TrickModificationNewVideoType::class,
                'allow_add'     => true,
                'entry_options' => [
                    'label' => false,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrickModificationDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new TrickModificationDTO(
                    $form->get('description')->getData(),
                    $form->get('published')->getData(),
                    $form->get('category')->getData(),
                    $form->get('newPictures')->getData(),
                    $form->get('newVideos')->getData()
                );
            },
        ]);
    }
}
