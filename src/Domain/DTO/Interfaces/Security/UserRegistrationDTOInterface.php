<?php

namespace App\Domain\DTO\Interfaces\Security;

interface UserRegistrationDTOInterface
{
    /**
     * UserRegistrationDTOInterface constructor.
     *
     * @param null|string $username
     * @param null|string $firstName
     * @param null|string $lastName
     * @param null|string $mail
     * @param null|string $password
     */
    public function __construct(
        ?string $username,
        ?string $firstName,
        ?string $lastName,
        ?string $mail,
        ?string $password
    );
}