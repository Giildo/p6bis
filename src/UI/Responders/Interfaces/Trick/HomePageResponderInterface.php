<?php

namespace App\UI\Responders\Interfaces\Trick;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface HomePageResponderInterface
{
    /**
     * @param array|null $tricks
     * @param int|null $numberPage
     * @param int|null $currentPage
     *
     * @return Response|RedirectResponse
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     *
     */
    public function homePageResponse(
        ?array $tricks = [],
        ?int $numberPage = 0,
        ?int $currentPage = 0
    ): Response;
}
