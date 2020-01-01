<?php

namespace App\Domain\DTO\Interfaces\User;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;

interface ProfilePictureDTOInterface
{
    /**
     * ProfilePictureDTOInterface constructor.
     * @param null|string $firstName
     * @param null|string $lastName
     * @param null|string $mail
     * @param PictureDTOInterface|null $picture
     */
    public function __construct(
        ?string $firstName = '',
        ?string $lastName = '',
        ?string $mail = '',
        ?PictureDTOInterface $picture = null
    );
}
