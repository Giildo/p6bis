<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\TrickNewVideoDTOInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;

interface VideoBuilderInterface
{
    /**
     * @param TrickNewVideoDTOInterface $dto
     * @param TrickInterface $trick
     * @return VideoBuilderInterface
     */
    public function build(
        TrickNewVideoDTOInterface $dto,
        TrickInterface $trick
    );

    /**
     * @return VideoInterface
     */
    public function getVideo(): VideoInterface;
}
