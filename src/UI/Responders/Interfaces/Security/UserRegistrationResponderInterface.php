<?php

namespace App\UI\Responders\Interfaces\Security;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

interface UserRegistrationResponderInterface
{
    /**
     * @param bool|null $redirection
     * @param FormInterface|null $form
     * @return Response|RedirectResponse
     */
    public function userRegistrationResponse(
        ?bool $redirection = true,
        ?FormInterface $form = null
    );
}