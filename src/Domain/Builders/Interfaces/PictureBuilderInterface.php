<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\NewTrickPictureDTOInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;

interface PictureBuilderInterface
{
    /**
     * @param NewTrickPictureDTOInterface $dto
     * @param Trick $trick
     * @param int $counter
     * @return Picture
     */
    public function build(
        NewTrickPictureDTOInterface $dto,
        Trick $trick,
        int $counter
    ): Picture;
}
