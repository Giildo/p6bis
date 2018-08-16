<?php

namespace App\Application\Handlers\Forms\Comment;

use App\Application\Handlers\Interfaces\Forms\Comment\AddCommentHandlerInterface;
use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    private $repository;

    /**
     * AddCommentHandler constructor.
     * @param CommentRepository $repository
     * @param CommentBuilderInterface $builder
     */
    public function __construct(
        CommentRepository $repository,
        CommentBuilderInterface $builder
    ) {
        $this->builder = $builder;
        $this->repository = $repository;
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

            $this->repository->saveComment($comment);

            return true;
        }

        return false;
    }
}
