<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;

interface PictureBuilderInterface
{
    /**
     * @param TrickNewPictureDTOInterface $dto
     * @param TrickInterface $trick
     * @param int $counter
     * @return PictureBuilderInterface
     */
    public function build(
        TrickNewPictureDTOInterface $dto,
        TrickInterface $trick,
        int $counter
    );

    /**
     * @return PictureInterface
     */
    public function getPicture(): PictureInterface;
}
