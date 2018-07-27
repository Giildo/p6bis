<?php

namespace App\UI\Responders\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Responders\Interfaces\Trick\ShowTrickResponderInterface;
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
     * @param bool|null $redirect
     * @param Trick|null $trick
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTrickResponse(
        ?bool $redirect = true,
        ?Trick $trick = null
    ) {
        return $redirect ?
            new RedirectResponse($this->urlGenerator->generate('home')) :
            new Response($this->presenter->showTrickPresentation($trick));
    }
}
