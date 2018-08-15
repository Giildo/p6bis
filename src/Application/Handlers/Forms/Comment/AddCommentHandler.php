<?php

namespace App\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class AddCommentHandler implements AddCommentHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CommentBuilderInterface
     */
    private $builder;

    /**
     * AddCommentHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param CommentBuilderInterface $builder
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CommentBuilderInterface $builder
    ) {
        $this->entityManager = $entityManager;
        $this->builder = $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        FormInterface $form,
        TrickInterface $trick
    ): bool {
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $comment = $this->builder->build($dto, $trick)
                                     ->getComment();

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }
}
