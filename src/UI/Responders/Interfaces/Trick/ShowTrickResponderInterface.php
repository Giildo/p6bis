<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface ShowTrickResponderInterface
{
    /**
     * @param bool|null $redirect
     * @param null|string $path
     * @param array|null $parameters
     * @param TrickInterface|null $trick
     * @param CommentInterface[]|null $comments
     * @param null|FormInterface $formComment
     * @param int|null $numberPage
     * @param int|null $paging
     *
     * @return RedirectResponse|Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function showTrickResponse(
        ?bool $redirect = true,
        ?string $path = 'Home',
        ?array $parameters = [],
        ?TrickInterface $trick = null,
        ?array $comments = [],
        ?FormInterface $formComment = null,
        ?int $numberPage = 0,
        ?int $paging = 0
    );
}