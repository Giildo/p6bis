<?php

namespace App\Domain\Model;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Video
 * @package App\Domain\Model
 *
 * @ORM\Table(name="p6bis_video")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\VideoRepository")
 */
class Video implements VideoInterface
{
    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\Column(type="string", length=20)
     */
    private $name;

    /**
     * @var string
     */
    private $deleteToken;

    /**
     * @var TrickInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Trick", cascade={"persist"}, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="slug", name="trick_slug")
     */
    private $trick;

    /**
     * Video constructor.
     * @param string $name
     * @param TrickInterface $trick
     */
    public function __construct(
        string $name,
        TrickInterface $trick
    ) {
        $this->name = $name;
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
    public function getDeleteToken(): string
    {
        return $this->deleteToken;
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
    public function createToken(string $token): void
    {
        $this->deleteToken = $token;
    }
}
