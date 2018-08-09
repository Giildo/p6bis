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
     * @var Picture
     */
    private $picture;

    /**
     * {@inheritdoc}
     */
    public function build(
        NewTrickPictureDTOInterface $dto,
        Trick $trick,
        int $counter
    ): self {
        $name = $trick->getSlug() . (new DateTime())->format('YmdHis') . '_' . $counter;

        $this->picture = new Picture(
            $name,
            $dto->description,
            $dto->picture->guessExtension(),
            false,
            $trick
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPicture(): Picture
    {
        return $this->picture;
    }
}
