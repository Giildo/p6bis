<?php

namespace App\Domain\Model\Interfaces;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


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

	/**
	 * @return Trick
	 */
	public function getTrick(): Trick;

	/**
	 * @param string $description
	 * @param bool $headPicture
	 *
	 * @return Picture
	 */
	public function update(
		string $description,
		bool $headPicture
	);

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

	/**
	 * @return string
	 */
	public function getDeleteToken(): string;
}