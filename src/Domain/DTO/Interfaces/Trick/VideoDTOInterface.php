<?php

namespace App\Domain\DTO\Interfaces\Trick;

interface VideoDTOInterface
{
    /**
     * NewVideoDTO constructor.
     *
     * @param string|null $url
     */
    public function __construct(?string $url = '');
}