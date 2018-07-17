<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForPasswordDTOInterface;

class PasswordRecoveryForPasswordDTO implements PasswordRecoveryForPasswordDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *     min="8",
     *     minMessage="Le mot de passe doit avoir au moins {{ limit }} caractères."
     * )
     */
    public $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
