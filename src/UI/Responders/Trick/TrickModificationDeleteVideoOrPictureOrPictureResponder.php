<?php

namespace App\UI\Responders\Trick;

use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeleteVideoOrPictureOrPictureResponder
    implements TrickModificationDeleteVideoOrPictureResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * TrickModificationDeleteVideoResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function response(string $trickSlug): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(
                'trick_modification',
                ['trickSlug' => $trickSlug]
            )
        );
    }
}
