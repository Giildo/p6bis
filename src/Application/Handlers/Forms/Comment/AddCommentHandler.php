<?php

namespace App\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\CommentRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
        TrickInterface $trick,
        Request $request
    ): bool {
        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            /** @var CommentInterface $comment */
            $comment = $request->getSession()->get('comment');

            if (is_null($comment)) {
                $comment = $this->builder->build($dto, $trick)
                                         ->getComment();

                $this->commentRepository->saveComment($comment);

                return true;
            }

            $comment->updateComment($dto);

            $this->commentRepository->saveComment($comment);

            return true;
        }

        return false;
    }
}
