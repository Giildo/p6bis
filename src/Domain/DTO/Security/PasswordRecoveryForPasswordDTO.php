<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForPasswordDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PasswordRecoveryForPasswordDTO implements PasswordRecoveryForPasswordDTOInterface
{
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

    public function __construct(?string $password)
    {
        $this->password = $password;
    }
}
