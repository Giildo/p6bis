<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
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
     * @var array|null
     *
     * @Assert\Type("array")
     */
    public $pictures;

    /**
     * @var array|null
     *
     * @Assert\Type("array")
     */
    public $videos;

    /**
     * NewTrickDTO constructor.
     * @param null|string $name
     * @param null|string $description
     * @param bool|null $published
     * @param CategoryInterface|null $category
     * @param array|null $pictures
     * @param array|null $videos
     */
    public function __construct(
        ?string $name = '',
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $pictures = [],
        ?array $videos = []
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->published = $published;
        $this->category = $category;
        $this->pictures = $pictures;
        $this->videos = $videos;
    }
}
