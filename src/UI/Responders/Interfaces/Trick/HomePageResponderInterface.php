<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface HomePageResponderInterface
{
    /**
     * @param array $tricks
     * @return Response|RedirectResponse
     */
    public function homePageResponse(?array $tricks = []): Response;
}
