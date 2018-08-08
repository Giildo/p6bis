<?php

namespace App\Domain\Model;

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
     * @var Trick
     *
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Trick", cascade={"persist"}, inversedBy="videos")
     * @ORM\JoinColumn(nullable=false, referencedColumnName="slug", name="trick_slug")
     */
    private $trick;

    /**
     * Video constructor.
     * @param string $name
     * @param Trick $trick
     */
    public function __construct(
        string $name,
        Trick $trick
    ) {
        $this->name = $name;
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
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
