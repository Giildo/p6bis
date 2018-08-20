<?php

namespace App\UI\Responders\Interfaces\Comment;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface CommentDeletionResponderInterface
{
    /**
     * @param string $trickSlug
     *
     * @return RedirectResponse
     */
    public function response(string $trickSlug): RedirectResponse;
}