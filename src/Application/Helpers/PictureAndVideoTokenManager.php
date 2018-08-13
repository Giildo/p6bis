<?php

namespace App\Application\Helpers;

use App\Application\Helpers\Interfaces\PictureAndVideoTokenManagerInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Video;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PictureAndVideoTokenManager implements PictureAndVideoTokenManagerInterface
{
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * PictureAndVideoTokenManager constructor.
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function createTokens(
        array $pictures,
        array $videos
    ): array {
        $tokens = [];

        if (!empty($pictures)) {
            /** @var Picture $picture */
            foreach ($pictures as $picture) {
                $picture->createToken($this->tokenGenerator->generateToken());

                $tokens[$picture->getName()] = $picture->getDeleteToken();
            }
        }

        if (!empty($videos)) {
            /** @var Video $video */
            foreach ($videos as $video) {
                $video->createToken($this->tokenGenerator->generateToken());

                $tokens[$video->getName()] = $video->getDeleteToken();
            }
        }

        return $tokens;
    }
}
