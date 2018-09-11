<?php

namespace App\Domain\DTO\Interfaces\Trick;

use App\Domain\Model\Interfaces\CategoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @param TrickNewPictureDTOInterface|null $headPicture
     */
    public function __construct(
        ?string $name = '',
        ?string $description = '',
        ?bool $published = false,
        ?CategoryInterface $category = null,
        ?array $pictures = [],
        ?array $videos = [],
        ?TrickNewPictureDTOInterface $headPicture = null
    );
}
