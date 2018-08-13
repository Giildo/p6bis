<?php

namespace App\Domain\DTO\Interfaces\Security;

interface UserConnectionDTOInterface
{
    /**
     * UserConnectionDTOInterface constructor.
     *
     * @param null|string $username
     * @param null|string $password
     */
    public function __construct(
        ?string $username,
        ?string $password
    );
}