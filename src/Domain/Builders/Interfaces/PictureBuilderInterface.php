<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;

interface PictureBuilderInterface
{
    /**
     * @param PictureDTOInterface $dto
     * @param TrickInterface|null $trick
     * @param int|null $counter
     * @param bool|null $headPicture
     * @param UserInterface|null $user
     *
     * @return PictureBuilderInterface
     */
    public function build(
        PictureDTOInterface $dto,
        ?TrickInterface $trick = null,
        ?int $counter = 0,
        ?bool $headPicture = false,
        ?UserInterface $user = null
    ): self;

    /**
     * @return PictureInterface
     */
    public function getPicture(): PictureInterface;
}
