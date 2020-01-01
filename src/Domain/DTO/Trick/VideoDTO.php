<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;

class VideoDTO implements VideoDTOInterface
{
    /**
     * @var null|string
     */
    public $url;

    /**
     * {@inheritdoc}
     */
    public function __construct(?string $url = '')
    {
        $this->url = $url;
    }
}
