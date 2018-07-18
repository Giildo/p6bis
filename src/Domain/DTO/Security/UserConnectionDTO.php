<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserConnectionDTO implements UserConnectionDTOInterface
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

    /**
     * @var string
     *
     * @Assert\NotNull(message="Le mot de passe doit être renseigné")
     * @Assert\Length(
     *     min="8",
     *     minMessage="Le mot de passe doit avoir au moins {{ limit }} caractères."
     * )
     */
    public $password;

    /**
     * UserConnectionDTO constructor.
     * @param string $username
     * @param string $password
     */
    public function __construct(?string $username, ?string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
}
