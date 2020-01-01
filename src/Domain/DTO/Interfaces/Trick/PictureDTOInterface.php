<?php

namespace App\Domain\DTO\Interfaces\Trick;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface PictureDTOInterface
{
    /**
     * NewPictureDTO constructor.
     *
     * @param null|string $description
     * @param null|UploadedFile $picture
     */
    public function __construct(
        ?string $description = '',
        ?UploadedFile $picture = null
    );
}