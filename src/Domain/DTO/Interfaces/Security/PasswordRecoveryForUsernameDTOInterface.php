<?php

namespace App\Domain\DTO\Interfaces\Security;

interface PasswordRecoveryForUsernameDTOInterface
{
    /**
     * PasswordRecoveryForUsernameDTOInterface constructor.
     * @param null|string $username
     */
    public function __construct(?string $username);
}
