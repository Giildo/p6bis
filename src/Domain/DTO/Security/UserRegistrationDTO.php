<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;

class UserRegistrationDTO implements UserRegistrationDTOInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $mail;

    /**
     * @var string
     */
    public $password;

    /**
     * UserRegistrationDTO constructor.
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     * @param string $mail
     * @param string $password
     */
    public function __construct(
        string $username,
        string $firstName,
        string $lastName,
        string $mail,
        string $password
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->password = $password;
    }
}
