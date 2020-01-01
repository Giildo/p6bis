<?php

namespace App\UI\Forms\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\DTO\Trick\NewTrickDTO;
use App\Domain\Model\Category;
use App\Domain\Model\Picture;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewTrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required'       => false,
                'error_bubbling' => true,
                'attr'           => [
                    'placeholder' => 'Nom de la figure*',
                    'class'       => 'form-control',
                ],
            ])
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
            ->add('headPicture', NewTrickNewPictureType::class, [
                'required' => false,
                'label'    => 'Image d\'en-tête',
            ])
            ->add('pictures', CollectionType::class, [
                'label'         => 'Images',
                'entry_type'    => NewTrickNewPictureType::class,
                'allow_add'     => true,
                'entry_options' => [
                    'label' => false,
                ],
            ])
            ->add('videos', CollectionType::class, [
                'label'         => 'Videos',
                'entry_type'    => NewTrickNewVideoType::class,
                'allow_add'     => true,
                'entry_options' => [
                    'label' => false,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewTrickDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new NewTrickDTO(
                    $form->get('name')->getData(),
                    $form->get('description')->getData(),
                    $form->get('published')->getData(),
                    $form->get('category')->getData(),
                    $form->get('pictures')->getData(),
                    $form->get('videos')->getData(),
                    $form->get('headPicture')->getData()
                );
            }
        ]);
    }
}
