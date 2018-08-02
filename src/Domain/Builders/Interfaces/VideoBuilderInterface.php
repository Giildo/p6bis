<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\Video;

interface VideoBuilderInterface
{
    /**
     * @param NewTrickVideoDTOInterface $dto
     * @param Trick $trick
     * @return Video
     */
    public function build(
        NewTrickVideoDTOInterface $dto,
        Trick $trick
    ): Video;
}
