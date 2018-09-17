<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class NewPictureDTO implements PictureDTOInterface
{
    /**
     * @var null|string
     *
     * @Assert\NotNull(message="La description de l'image doit être renseignée.")
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
