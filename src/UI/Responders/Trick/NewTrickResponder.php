<?php

namespace App\UI\Responders\Trick;

use App\UI\Presenters\Interfaces\Trick\NewTrickPresenterInterface;
use App\UI\Responders\Interfaces\Trick\NewTrickResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NewTrickResponder implements NewTrickResponderInterface
{
    /**
     * @var NewTrickPresenterInterface
     */
    private $presenter;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * NewTrickResponder constructor.
     * @param NewTrickPresenterInterface $presenter
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        NewTrickPresenterInterface $presenter,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->presenter = $presenter;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function response(
        bool $redirection,
        ?FormInterface $form = null
    ): Response {
        return ($redirection) ?
            new RedirectResponse($this->urlGenerator->generate('Home')) :
            new Response($this->presenter->newTrickPresentation($form));
    }
}
