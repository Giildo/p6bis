<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface TrickModificationResponderInterface
{
    /**
     * @param bool|null $redirection
     * @param null|string $redirectionURL
     * @param array|null $parameters
     * @param null|FormInterface $form
     * @param TrickInterface|null $trick
     *
     * @return Response|RedirectResponse
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function trickModificationResponse(
        ?bool $redirection = true,
        ?string $redirectionURL = '/',
        ?array $parameters = [],
        ?FormInterface $form = null,
        ?TrickInterface $trick = null
    ): Response;
}
