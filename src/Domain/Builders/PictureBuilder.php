<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\NewTrickPictureDTOInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use DateTime;

class PictureBuilder implements PictureBuilderInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(
        NewTrickPictureDTOInterface $dto,
        Trick $trick,
        int $counter
    ): Picture {
        $name = $trick->getName() . (new DateTime())->format('YmdHis') . '_' . $counter;

        return new Picture(
            $name,
            $dto->description,
            $dto->picture->guessExtension(),
            false,
            $trick
        );
    }
}
