<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;

class UserConnectionDTO implements UserConnectionDTOInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * UserConnectionDTO constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
