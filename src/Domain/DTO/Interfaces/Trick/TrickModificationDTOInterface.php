<?php

namespace App\Domain\DTO\Interfaces\Trick;

use App\Domain\Model\Interfaces\CategoryInterface;

interface TrickModificationDTOInterface
{
    /**
     * TrickModificationDTO constructor.
     *
     * @param null|string $description
     * @param bool|null $published
     * @param CategoryInterface|null $category
     * @param TrickNewPictureDTOInterface[]|null $newPictures
     * @param TrickNewVideoDTOInterface[]|null $newVideos
     */
    public function __construct(
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $newPictures = [],
        ?array $newVideos = []
    );
}