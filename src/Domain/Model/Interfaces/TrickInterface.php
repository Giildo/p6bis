<?php

namespace App\Domain\Model\Interfaces;

use App\Domain\Model\Trick;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Trick
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_trick")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\TrickRepository")
 */
interface TrickInterface
{
    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime;

    /**
     * @return CategoryInterface
     */
    public function getCategory(): CategoryInterface;

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface;

	/**
	 * @return PictureInterface[]|null
	 */
	public function getPictures(): ?array;

	/**
	 * @return VideoInterface[]|null
	 */
	public function getVideos(): ?array;

    /**
     * @return CommentInterface[]|null
     */
    public function getComments(): ?array;

	/**
	 * @return void
	 */
	public function publish(): void;

	/**
	 * @param string $description
	 * @param bool $published
	 * @param CategoryInterface $category
	 *
	 * @return Trick
	 */
	public function update(
		string $description,
		bool $published,
		CategoryInterface $category
	);
}