<?php

namespace App\Domain\DTO\Interfaces\Comment;

interface AddCommentDTOInterface
{
    /**
     * AddCommentDTO constructor.
     * @param string $comment
     */
    public function __construct(?string $comment = '');
}