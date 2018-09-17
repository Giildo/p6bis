<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;
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
        PictureDTOInterface $dto,
        ?TrickInterface $trick = null,
        ?int $counter = 0,
        ?bool $headPicture = false,
        ?UserInterface $user = null
    ): self {

        $name = (!is_null($trick)) ?
            $trick->getSlug() . (new DateTime())->format('YmdHis') . '_' . $counter :
            $user->getUsername() . (new DateTime())->format('YmdHis');

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
