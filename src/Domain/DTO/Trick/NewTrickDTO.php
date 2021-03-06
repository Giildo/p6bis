<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickDTO implements NewTrickDTOInterface
{
    /**
     * @var null|string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="Le nom de la figure doit être renseigné.")
     * @Assert\Length(
     *     min="3",
     *     minMessage="Le nom de la figure doit avoir au moins {{ limit }} caractères.",
     *     max="50",
     *     maxMessage="Le nom de la figure ne doit pas avoir plus de {{ limit }} caractères."
     * )
     */
    public $name;

    /**
     * @var null|string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="La description de la figure doit être renseignée.")
     * @Assert\Length(
     *     min="5",
     *     minMessage="La description doit contenir au moins {{ limit }} caractères."
     * )
     */
    public $description;

    /**
     * @var bool|null
     *
     * @Assert\Type("bool")
     */
    public $published;

    /**
     * @var CategoryInterface|null
     *
     * @Assert\Type("object")
     * @Assert\NotNull(message="La catégorie associée doit être renseignée.")
     */
    public $category;

    /**
     * @var PictureDTOInterface[]|null
     *
     * @Assert\Type("array")
     * @Assert\Valid()
     */
    public $pictures;

    /**
     * @var VideoDTOInterface[]|null
     *
     * @Assert\Type("array")
     * @Assert\Valid()
     */
    public $videos;

    /**
     * @var null|UploadedFile
     */
    public $headPicture;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ?string $name = '',
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $pictures = [],
        ?array $videos = [],
        ?PictureDTOInterface $headPicture = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->published = $published;
        $this->category = $category;
        $this->pictures = $pictures;
        $this->videos = $videos;
        $this->headPicture = $headPicture;
    }
}
