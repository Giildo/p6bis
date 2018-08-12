<?php

namespace App\Domain\Model\Interfaces;

use App\Domain\Model\Trick;

/**
 * Class Video
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_video")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\VideoRepository")
 */
interface VideoInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDeleteToken(): string;

    /**
     * @return Trick
     */
    public function getTrick(): Trick;

    /**
     * @param string $token
     *
     * @return void
     */
    public function createToken(string $token): void;

    /**
     * @return void
     */
    public function deleteToken(): void;
}