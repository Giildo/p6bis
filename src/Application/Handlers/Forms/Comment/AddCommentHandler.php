<?php

namespace App\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\CommentRepository;
use Symfony\Component\Form\FormInterface;

class AddCommentHandler implements AddCommentHandlerInterface
{
    /**
     * @var CommentBuilderInterface
     */
    private $builder;
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * AddCommentHandler constructor.
     * @param CommentRepository $commentRepository
     * @param CommentBuilderInterface $builder
     */
    public function __construct(
        CommentRepository $commentRepository,
        CommentBuilderInterface $builder
    ) {
        $this->builder = $builder;
        $this->commentRepository = $commentRepository;
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

            $this->commentRepository->saveComment($comment);

            return true;
        }

        return false;
    }
}
