<?php

namespace App\UI\Forms\Comment;

use App\Domain\DTO\Comment\AddCommentDTO;
use App\Domain\DTO\Interfaces\Comment\AddCommentDTOInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('comment', TextareaType::class, [
            'required' => false,
            'label'    => 'Votre commentaire',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AddCommentDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new AddCommentDTO(
                    $form->get('comment')->getData()
                );
            },
        ]);
    }

}
