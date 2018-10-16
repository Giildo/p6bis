<?php

namespace App\Application\Authenticator\Interfaces\Security;

use Symfony\Component\Security\Guard\AuthenticatorInterface;

interface UserConnectionTypeAuthenticatorInterface extends AuthenticatorInterface
{
    /**
     * Return the URL to the login page.
     *
     * @return string
     */
    public function getLoginUrl();
}