<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
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
        TrickNewPictureDTOInterface $dto,
        TrickInterface $trick,
        int $counter,
        ?bool $headPicture = false
    ): self {
        $name = $trick->getSlug() . (new DateTime())->format('YmdHis') . '_' . $counter;

        $this->picture = new Picture(
            $name,
            $dto->description,
            $dto->picture->guessExtension(),
            $headPicture,
            $trick
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPicture(): PictureInterface
    {
        return $this->picture;
    }
}
