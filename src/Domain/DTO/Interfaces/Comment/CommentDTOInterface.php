<?php

namespace App\Domain\DTO\Interfaces\Comment;

interface CommentDTOInterface
{
    /**
     * AddCommentDTO constructor.
     * @param string $comment
     */
    public function __construct(?string $comment = '');
}