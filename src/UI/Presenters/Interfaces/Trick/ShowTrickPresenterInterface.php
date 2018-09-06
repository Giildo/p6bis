<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface ShowTrickPresenterInterface
{
    /**
     * @param TrickInterface $trick
     * @param FormInterface $formComment
     * @param CommentInterface[] $comments
     * @param int $pageNumber
     * @param int|null $currentPage
     *
     * @return string
     */
    public function showTrickPresentation(
        TrickInterface $trick,
        FormInterface $formComment,
        array $comments,
        int $pageNumber,
        ?int $currentPage = 0
    ): string;
}