<?php

namespace App\Domain\DTO\Interfaces\Trick;

use App\Domain\Model\Interfaces\CategoryInterface;

interface NewTrickDTOInterface
{
    /**
     * NewTrickDTO constructor.
     *
     * @param null|string $name
     * @param null|string $description
     * @param bool|null $published
     * @param CategoryInterface|null $category
     * @param TrickNewPictureDTOInterface[]|null $pictures
     * @param TrickNewVideoDTOInterface[]|null $videos
     */
    public function __construct(
        ?string $name = '',
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $pictures = [],
        ?array $videos = []
    );
}
