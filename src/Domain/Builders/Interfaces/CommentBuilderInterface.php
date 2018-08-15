<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\Builders\CommentBuilder;
use App\Domain\DTO\Interfaces\Comment\AddCommentDTOInterface;
use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\TrickInterface;

interface CommentBuilderInterface
{
    /**
     * @param AddCommentDTOInterface $DTO
     * @param TrickInterface $trick
     *
     * @return CommentBuilder
     */
    public function build(AddCommentDTOInterface $DTO, TrickInterface $trick);

    /**
     * @return Comment
     */
    public function getComment(): Comment;
}