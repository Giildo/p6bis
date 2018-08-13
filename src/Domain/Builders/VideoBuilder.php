<?php

namespace App\Domain\Builders;

use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\TrickNewVideoDTOInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
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
        TrickNewVideoDTOInterface $dto,
        TrickInterface $trick
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
    public function getVideo(): VideoInterface
    {
        return $this->video;
    }
}
