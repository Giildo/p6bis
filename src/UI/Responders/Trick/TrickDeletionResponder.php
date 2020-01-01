<?php

namespace App\UI\Responders\Trick;

use App\UI\Responders\Interfaces\Trick\TrickDeletionResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickDeletionResponder implements TrickDeletionResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * TrickDeletionResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function response(): RedirectResponse
    {
        return new RedirectResponse($this->urlGenerator->generate('Home'));
    }
}
