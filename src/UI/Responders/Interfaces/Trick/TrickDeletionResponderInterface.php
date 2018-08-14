<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface TrickDeletionResponderInterface
{
    /**
     * @return RedirectResponse
     */
    public function response(): RedirectResponse;
}