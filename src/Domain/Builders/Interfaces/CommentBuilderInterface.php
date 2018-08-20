<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\Builders\CommentBuilder;
use App\Domain\DTO\Interfaces\Comment\CommentDTOInterface;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;

interface CommentBuilderInterface
{
    /**
     * @param CommentDTOInterface $DTO
     * @param TrickInterface $trick
     *
     * @return CommentBuilder
     */
    public function build(
        CommentDTOInterface $DTO,
        TrickInterface $trick
    );

    /**
     * @return CommentInterface
     */
    public function getComment(): CommentInterface;
}