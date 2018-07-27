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
     * @return Trick
     */
    public function getTrick(): Trick;
}