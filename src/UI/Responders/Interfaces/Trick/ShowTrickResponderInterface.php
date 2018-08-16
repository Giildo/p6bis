<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Trick;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface ShowTrickResponderInterface
{
    /**
     * @param bool|null $redirect
     * @param null|string $path
     * @param array|null $parameters
     * @param Trick|null $trick
     * @param null|FormInterface $formComment
     *
     * @return RedirectResponse|Response
     */
    public function showTrickResponse(
        ?bool $redirect = true,
        ?string $path = 'Home',
        ?array $parameters = [],
        ?Trick $trick = null,
        ?FormInterface $formComment = null
    );
}