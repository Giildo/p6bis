<?php

namespace App\UI\Responders\Trick;

use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ShowTrickResponder implements ShowTrickResponderInterface
{
    /**
     * @var ShowTrickPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ShowTrickResponder constructor.
     * @param ShowTrickPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        ShowTrickPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
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
    ) {
        return $redirect ?
            new RedirectResponse($this->urlGenerator->generate($path, $parameters)) :
            new Response($this->presenter->showTrickPresentation(
                $trick,
                $formComment,
                $comments,
                $numberPage,
                $paging
            ));
    }
}
