<?php

namespace App\Application\Mailers\Interfaces\Security;

interface PasswordRecoveryMailerInterface
{
    /**
     * @param string $mail
     * @return bool
     */
    public function message(string $mail): bool;
}