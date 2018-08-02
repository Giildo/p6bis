<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\Video;

class VideoBuilder implements VideoBuilderInterface
{

    /**
     * @param NewTrickVideoDTOInterface $dto
     * @param Trick $trick
     * @return Video
     */
    public function build(
        NewTrickVideoDTOInterface $dto,
        Trick $trick
    ): Video {
        $name = [];
        preg_match('/watch\?v\=([\w-]+)$/', $dto->url, $name);

        return new Video(
            $name[1],
            $trick
        );
    }
}
