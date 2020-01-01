<?php

namespace App\UI\Forms\Comment;

use App\Domain\DTO\Comment\CommentModificationDTO;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentModificationType extends AbstractType
{
    public function getParent()
    {
        return AddCommentType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentDTOInterface::class,
            'empty_data' => function (FormInterface $form) {
                return new CommentModificationDTO(
                    $form->get('comment')->getData()
                );
            },
        ]);
    }
}
