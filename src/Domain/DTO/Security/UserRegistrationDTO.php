<?php

namespace App\Domain\DTO\Security;

use App\Domain\DTO\Interfaces\Security\UserRegistrationDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserRegistrationDTO implements UserRegistrationDTOInterface
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Le nom d'utilisateur doit être renseigné.")
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
     * @Assert\Type("string")
     * @Assert\NotNull(message="Le prénom doit être renseigné.")
     * @Assert\Length(
     *     min="2",
     *     minMessage="Le prénom doit avoir au moins {{ limit }} caractères.",
     *     max="50",
     *     maxMessage="Le prénom ne doit pas avoir plus de {{ limit }} caractères."
     * )
     */
    public $firstName;

    /**
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Le nom doit être renseigné.")
     * @Assert\Length(
     *     min="2",
     *     minMessage="Le nom doit avoir au moins {{ limit }} caractères.",
     *     max="50",
     *     maxMessage="Le nom ne doit pas avoir plus de {{ limit }} caractères."
     * )
     */
    public $lastName;

    /**
     * @var string
     *
     * @Assert\Email(message="{{ value }} n'est pas une adresse mail valide.")
     * @Assert\NotNull(message="L'eMail doit être renseigné.")
     */
    public $mail;

    /**
     * @var string
     *
     * @Assert\NotNull(message="Le mot de passe doit être renseigné.")
     * @Assert\Length(
     *     min="8",
     *     minMessage="Le mot de passe doit avoir au moins {{ limit }} caractères."
     * )
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
        ?string $username,
        ?string $firstName,
        ?string $lastName,
        ?string $mail,
        ?string $password
    ) {
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->password = $password;
    }
}
