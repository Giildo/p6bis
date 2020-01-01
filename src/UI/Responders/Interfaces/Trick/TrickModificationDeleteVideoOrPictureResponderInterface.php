<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface TrickModificationDeleteVideoOrPictureResponderInterface
{
    /**
     * @param string $trickSlug
     *
     * @return RedirectResponse
     */
    public function response(string $trickSlug): RedirectResponse;
}