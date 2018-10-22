<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\CommentBuilderInterface;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentBuilder implements CommentBuilderInterface
{
    /**
     * @var CommentInterface
     */
    private $comment;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * CommentBuilder constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function build(
        CommentDTOInterface $DTO,
        TrickInterface $trick
    ): CommentBuilderInterface {
        /** @var UserInterface $author */
        $author = $this->tokenStorage->getToken()
                                     ->getUser()
        ;

        $this->comment = new Comment(
            $DTO->comment,
            $trick,
            $author
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment(): CommentInterface
    {
        return $this->comment;
    }
}
