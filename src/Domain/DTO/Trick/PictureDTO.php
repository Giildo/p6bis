<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureDTO implements PictureDTOInterface
{
    /**
     * @var null|string
     */
    public $description;

    /**
     * @var null|UploadedFile
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
