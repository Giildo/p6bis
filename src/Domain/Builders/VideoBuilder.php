<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\NewTrickVideoDTOInterface;
use App\Domain\Model\Trick;
use App\Domain\Model\Video;

class VideoBuilder implements VideoBuilderInterface
{
    /**
     * @var Video
     */
    private $video;

    /**
     * {@inheritdoc}
     */
    public function build(
        NewTrickVideoDTOInterface $dto,
        Trick $trick
    ): self {
        $name = [];
        preg_match('/watch\?v\=([\w\-]+)$/', $dto->url, $name);

        $this->video = new Video(
            $name[1],
            $trick
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getVideo(): Video
    {
        return $this->video;
    }
}
