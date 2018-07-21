<?php

namespace App\Domain\Model\Interfaces;


/**
 * Class Picture
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_picture")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\PictureRepository")
 */
interface PictureInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getExtension(): string;

    /**
     * @return bool
     */
    public function isHeadPicture(): bool;
}