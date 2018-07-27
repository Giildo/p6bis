<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\PictureInterface;
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
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $headPicture;

    /**
     * @var Trick
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
     * @param Trick $trick
     */
    public function __construct(
        string $name,
        string $description,
        string $extension,
        bool $headPicture,
        Trick $trick
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->extension = $extension;
        $this->headPicture = $headPicture;
        $this->trick = $trick;
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
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return bool
     */
    public function isHeadPicture(): bool
    {
        return $this->headPicture;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
