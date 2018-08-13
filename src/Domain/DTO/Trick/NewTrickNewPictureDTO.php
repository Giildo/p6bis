<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickNewPictureDTO implements TrickNewPictureDTOInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        ?string $description = '',
        ?UploadedFile $picture = null
    ) {
        $this->description = $description;
        $this->picture = $picture;
    }
}
