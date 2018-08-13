<?php

namespace App\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\Interfaces\PictureSaveHelperInterface;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\Trick\TrickNewPictureDTOInterface;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Modifier\Interfaces\TrickModifierInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickModificationHandler implements TrickModificationHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TrickModifierInterface
     */
    private $trickModifier;
    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;
    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;
    /**
     * @var PictureSaveHelperInterface
     */
    private $pictureSave;

    /**
     * TrickModificationHandler constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param TrickModifierInterface $trickModifier
     * @param PictureBuilderInterface $pictureBuilder
     * @param VideoBuilderInterface $videoBuilder
     * @param PictureSaveHelperInterface $pictureSave
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TrickModifierInterface $trickModifier,
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder,
        PictureSaveHelperInterface $pictureSave
    ) {
        $this->entityManager = $entityManager;
        $this->trickModifier = $trickModifier;
        $this->pictureBuilder = $pictureBuilder;
        $this->videoBuilder = $videoBuilder;
        $this->pictureSave = $pictureSave;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(
        FormInterface $form,
        TrickInterface $trick
    ): bool {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var TrickModificationDTO $dto */
            $dto = $form->getData();

            /** @var TrickInterface $trick */
            $trick = $this->trickModifier->modify($trick, $dto)
                                         ->getTrick();
            $this->entityManager->persist($trick);

            /** @var TrickNewPictureDTOInterface[] $pictureDTOs */
            $pictureDTOs = $dto->newPictures;
            $newPictures = [];
            if (!empty($pictureDTOs)) {
                $counter = 1;
                foreach ($pictureDTOs as $pictureDTO) {
                    $picture = $this->pictureBuilder->build($pictureDTO, $trick, $counter)
                                                    ->getPicture();

                    $this->entityManager->persist($picture);
                    $newPictures[] = [$picture, $pictureDTO->picture];

                    $counter++;
                }
            }

            $videoDTOs = $dto->newVideos;
            if (!empty($videoDTOs)) {
                foreach ($videoDTOs as $videoDTO) {
                    $video = $this->videoBuilder->build($videoDTO, $trick)
                                                ->getVideo();

                    $this->entityManager->persist($video);
                }
            }

            $this->entityManager->flush();

            if (!empty($newPictures)) {
                $this->pictureSave->save($newPictures);
            }

            return true;
        }

        return false;
    }
}
