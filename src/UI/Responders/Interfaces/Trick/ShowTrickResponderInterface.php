<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Trick;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface ShowTrickResponderInterface
{
    /**
     * @param bool|null $redirect
     * @param Trick|null $trick
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTrickResponse(
        ?bool $redirect = true,
        ?Trick $trick = null
    );
}