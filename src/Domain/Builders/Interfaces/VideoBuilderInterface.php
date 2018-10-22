<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;

interface VideoBuilderInterface
{
    /**
     * @param VideoDTOInterface $dto
     * @param TrickInterface $trick
     * @return VideoBuilderInterface
     */
    public function build(
        VideoDTOInterface $dto,
        TrickInterface $trick
    ): self;

    /**
     * @return VideoInterface
     */
    public function getVideo(): VideoInterface;
}
