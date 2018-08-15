<?php

namespace App\Domain\Model\Interfaces;


use DateTime;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Comment
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_comment")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CommentRepository")
 */
interface CommentInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getComment(): string;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime;

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime;

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface;

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface;
}