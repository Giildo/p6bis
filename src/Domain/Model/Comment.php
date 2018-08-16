<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Comment
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_comment")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\CommentRepository")
 */
class Comment implements CommentInterface
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var TrickInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Trick", cascade={"persist"}, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="slug", name="trick_slug")
     */
    private $trick;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id", name="author_id")
     */
    private $author;

    /**
     * Comment constructor.
     * @param string $comment
     * @param TrickInterface $trick
     * @param UserInterface $author
     */
    public function __construct(
        string $comment,
        TrickInterface $trick,
        UserInterface $author
    ) {
        $this->comment = $comment;
        $this->trick = $trick;
        $this->author = $author;
        $this->createdAt = new DateTime();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface
    {
        return $this->trick;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }
}
