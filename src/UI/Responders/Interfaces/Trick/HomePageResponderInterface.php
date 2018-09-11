<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface HomePageResponderInterface
{
    /**
     * @param array|null $tricks
     * @param bool|null $redirect
     * @param int|null $numberPage
     * @param int|null $currentPage
     *
     * @return Response|RedirectResponse
     */
    public function homePageResponse(
        ?array $tricks = [],
        ?bool $redirect = false,
        ?int $numberPage = 0,
        ?int $currentPage = 0
    ): Response;
}
