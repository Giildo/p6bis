<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForUsernameDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordRecoveryForUsernameDTO implements PasswordRecoveryForUsernameDTOInterface
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Le nom d'utilisateur doit être renseigné")
     * @Assert\Length(
     *     min="5",
     *     minMessage="Le nom d'utilisateur doit avoir au moins {{ limit }} caractères.",
     *     max="50",
     *     maxMessage="Le nom d'utilisateur ne doit pas avoir plus de {{ limit }} caractères."
     * )
     */
    public $username;

    public function __construct(?string $username)
    {
        $this->username = $username;
    }
}
