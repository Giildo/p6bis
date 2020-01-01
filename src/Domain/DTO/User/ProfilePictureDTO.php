<?php

namespace App\Domain\DTO\User;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Interfaces\User\ProfilePictureDTOInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ProfilePictureDTO implements ProfilePictureDTOInterface
{

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
     * @var null|PictureDTOInterface
     *
     * @Assert\Valid()
     */
    public $profilePicture;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ?string $firstName = '',
        ?string $lastName = '',
        ?string $mail = '',
        ?PictureDTOInterface $profilePicture = null
    ) {

        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->mail = $mail;
        $this->profilePicture = $profilePicture;
    }
}
