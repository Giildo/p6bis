<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NewTrickDTO implements NewTrickDTOInterface
{
    /**
     * @var string
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
     * @var string
     *
     * @Assert\Type("string")
     * @Assert\NotNull(message="La description de la figure doit être renseignée.")
     */
    public $description;

    /**
     * @var bool
     *
     * @Assert\Type("bool")
     */
    public $published;

    /**
     * @var CategoryInterface
     *
     * @Assert\Type("object")
     * @Assert\NotNull(message="La catégorie associée doit être renseignée.")
     */
    public $category;

    /**
     * NewTrickDTO constructor.
     * @param string $name
     * @param string $description
     * @param bool $published
     * @param CategoryInterface $category
     */
    public function __construct(
        string $name,
        string $description,
        bool $published,
        CategoryInterface $category
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->published = $published;
        $this->category = $category;
    }
}
