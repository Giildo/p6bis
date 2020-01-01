<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickModificationDTOInterface;
use App\Domain\DTO\Interfaces\Trick\PictureDTOInterface;
use App\Domain\DTO\Interfaces\Trick\VideoDTOInterface;
use App\Domain\Model\Interfaces\CategoryInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TrickModificationDTO implements TrickModificationDTOInterface
{
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
     */
    public $newPictures;

    /**
     * @var VideoDTOInterface[]|null
     *
     * @Assert\Type("array")
     */
    public $newVideos;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $newPictures = [],
        ?array $newVideos = []
    ) {
        $this->description = $description;
        $this->published = $published;
        $this->category = $category;
        $this->newPictures = $newPictures;
        $this->newVideos = $newVideos;
    }
}
