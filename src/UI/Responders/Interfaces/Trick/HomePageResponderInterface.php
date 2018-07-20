<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface HomePageResponderInterface
{
    /**
     * @return Response|RedirectResponse
     */
    public function homePageResponse(): Response;
}
