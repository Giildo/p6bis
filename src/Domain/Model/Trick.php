<?php

namespace App\Domain\Model;

use App\Application\Helpers\Interfaces\SluggerHelperInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Class Trick
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_trick")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\TrickRepository")
 */
class Trick implements TrickInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string", length=50)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $published;

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
     * @var CategoryInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Category", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="slug", name="category_slug")
     */
    private $category;

    /**
     * @var UserInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Picture", mappedBy="trick")
     */
    private $pictures;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Video", mappedBy="trick")
     */
    private $videos;

    /**
     * @var PersistentCollection
     *
     * @ORM\OneToMany(targetEntity="App\Domain\Model\Comment", mappedBy="trick")
     */
    private $comments;

    /**
     * Trick constructor.
     * @param string $slug
     * @param string $name
     * @param string $description
     * @param CategoryInterface $category
     * @param UserInterface $author
     */
    public function __construct(
        string $slug,
        string $name,
        string $description,
        CategoryInterface $category,
        UserInterface $author
    ) {
        $this->slug = $slug;
        $this->name = $name;
        $this->description = $description;
        $this->published = false;
        $this->createdAt = new DateTime();
        $this->category = $category;
        $this->author = $author;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    public function getPictures(): ?array
    {
        return $this->pictures->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getVideos(): ?array
    {
        return $this->videos->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getComments(): ?array
    {
        return $this->comments->toArray();
    }

	/**
	 * {@inheritdoc}
	 */
	public function publish(): void
    {
        $this->published = true;
    }

	/**
	 * {@inheritdoc}
	 */
	public function update(
		string $description,
		bool $published,
		CategoryInterface $category
	): self {
		$this->description = $description;
		$this->published = $published;
		$this->category = $category;

		$this->updatedAt = new DateTime();

		return $this;
    }
}
