<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface ShowTrickPresenterInterface
{
    /**
     * @param TrickInterface $trick
     * @param FormInterface $formComment
     * @param int $pageNumber
     * @param CommentInterface[] $comments
     * @param int|null $currentPage
     *
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function showTrickPresentation(
        TrickInterface $trick,
        FormInterface $formComment,
        int $pageNumber,
        ?array $comments = [],
        ?int $currentPage = 0
    ): string;
}