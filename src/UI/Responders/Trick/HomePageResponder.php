<?php

namespace App\UI\Responders\Trick;

use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomePageResponder implements HomePageResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var HomePagePresenterInterface
     */
    private $presenter;

    /**
     * HomePageResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     * @param HomePagePresenterInterface $presenter
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        HomePagePresenterInterface $presenter
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function homePageResponse(
        ?array $tricks = [],
        ?bool $redirect = false,
        ?int $numberPage = 0,
        ?int $currentPage = 0
    ): Response {
        return ($redirect) ?
            new RedirectResponse($this->urlGenerator->generate('Home')) :
            new Response($this->presenter->homePagePresentation($tricks, $numberPage, $currentPage));
    }
}
