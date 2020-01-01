<?php

namespace App\UI\Responders\Comment;

use App\UI\Responders\Interfaces\Comment\CommentDeletionResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CommentDeletionResponder implements CommentDeletionResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * CommentDeletionResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function response(string $trickSlug): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate(
                'Trick_show', ['trickSlug' => $trickSlug]
            )
        );
    }
}
