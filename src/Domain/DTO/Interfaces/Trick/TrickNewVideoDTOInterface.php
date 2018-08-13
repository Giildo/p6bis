<?php

namespace App\Domain\DTO\Interfaces\Trick;

interface TrickNewVideoDTOInterface
{
    /**
     * NewTrickNewVideoDTO constructor.
     *
     * @param string|null $url
     */
    public function __construct(?string $url = '');
}