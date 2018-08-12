<?php

namespace App\Application\Helpers\Interfaces;

interface PictureAndVideoTokenManagerInterface
{
    /**
     * @param array $pictures
     * @param array $videos
     *
     * @return array
     */
    public function createTokens(
        array $pictures,
        array $videos
    ): array;

    /**
     * @param array $pictures
     * @param array $videos
     *
     * @return void
     */
    public function deleteTokens(
        array $pictures,
        array $videos
    ): void;
}