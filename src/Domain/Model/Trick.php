<?php

namespace App\Domain\Model;

use App\Application\Helpers\Interfaces\SluggerHelperInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * Trick constructor.
     * @param string $name
     * @param string $description
     * @param SluggerHelperInterface $slugger
     * @param CategoryInterface $category
     * @param UserInterface $author
     */
    public function __construct(
        string $name,
        string $description,
        SluggerHelperInterface $slugger,
        CategoryInterface $category,
        UserInterface $author
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->published = false;
        $this->createdAt = new DateTime();
        $this->category = $category;
        $this->author = $author;

        $this->createSlug($slugger, $this->name);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->published;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return CategoryInterface
     */
    public function getCategory(): CategoryInterface
    {
        return $this->category;
    }

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * @return array|null
     */
    public function getPictures(): ?array
    {
        return $this->pictures->toArray();
    }

    /**
     * @return array|null
     */
    public function getVideos(): ?array
    {
        return $this->videos->toArray();
    }

    public function createSlug(SluggerHelperInterface $slugger, string $name): void
    {
        $this->slug = $slugger->slugify($name);
    }

    public function publish(): void
    {
        $this->published = true;
    }
}
