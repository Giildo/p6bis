<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickPictureDTOInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickPictureDTO implements NewTrickPictureDTOInterface
{
    /**
     * @var null|string
     *
     * @Assert\NotNull(message="La description doit être renseignée.")
     */
    public $description;

    /**
     * @var null|UploadedFile
     *
     * @Assert\NotNull(message="Un fichier valide doit être chargé.")
     */
    public $picture;

    /**
     * NewTrickPictureDTO constructor.
     * @param null|string $description
     * @param null|UploadedFile $picture
     */
    public function __construct(
        ?string $description = '',
        ?UploadedFile $picture = null
    ) {
        $this->description = $description;
        $this->picture = $picture;
    }
}
