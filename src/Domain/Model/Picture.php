<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Picture
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_picture")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\PictureRepository")
 */
class Picture implements PictureInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=5)
     */
    private $extension;

	/**
	 * @var string
	 */
	private $deleteToken;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $headPicture;

    /**
     * @var TrickInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Trick", cascade={"persist"}, inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="slug", name="trick_slug")
     */
    private $trick;

    /**
     * PictureTest constructor.
     * @param string $name
     * @param string $description
     * @param string $extension
     * @param bool $headPicture
     * @param TrickInterface $trick
     */
    public function __construct(
        string $name,
        string $description,
        string $extension,
        bool $headPicture,
        TrickInterface $trick
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->extension = $extension;
        $this->headPicture = $headPicture;
        $this->trick = $trick;
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
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * {@inheritdoc}
     */
    public function isHeadPicture(): bool
    {
        return $this->headPicture;
    }

    /**
     * {@inheritdoc}
     */
    public function getTrick(): TrickInterface
    {
        return $this->trick;
    }

	/**
	 * {@inheritdoc}
	 */
	public function getDeleteToken(): string
	{
		return $this->deleteToken;
	}

	/**
	 * {@inheritdoc}
	 */
	public function update(
		string $description,
		bool $headPicture
	): self {
		$this->description = $description;
		$this->headPicture = $headPicture;

		return $this;
    }

	/**
	 * {@inheritdoc}
	 */
	public function createToken(string $token): void
	{
		$this->deleteToken = $token;
	}
}
