<?php

namespace App\Domain\DTO\Interfaces\Security;

interface PasswordRecoveryForPasswordDTOInterface
{
    /**
     * PasswordRecoveryForPasswordDTOInterface constructor.
     * @param null|string $password
     */
    public function __construct(?string $password);
}
