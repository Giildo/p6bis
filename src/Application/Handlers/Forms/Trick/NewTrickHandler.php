<?php

namespace App\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\Application\Helpers\Interfaces\PictureSaveHelperInterface;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\Builders\Interfaces\TrickBuilderInterface;
use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class NewTrickHandler implements NewTrickHandlerInterface
{
    /**
     * @var TrickBuilderInterface
     */
    private $trickBuilder;
    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;
    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var PictureSaveHelperInterface
     */
    private $saveHelper;

    /**
     * NewTrickHandler constructor.
     * @param TrickBuilderInterface $trickBuilder
     * @param PictureBuilderInterface $pictureBuilder
     * @param VideoBuilderInterface $videoBuilder
     * @param EntityManagerInterface $entityManager
     * @param PictureSaveHelperInterface $saveHelper
     */
    public function __construct(
        TrickBuilderInterface $trickBuilder,
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder,
        EntityManagerInterface $entityManager,
        PictureSaveHelperInterface $saveHelper
    ) {
        $this->trickBuilder = $trickBuilder;
        $this->pictureBuilder = $pictureBuilder;
        $this->videoBuilder = $videoBuilder;
        $this->entityManager = $entityManager;
        $this->saveHelper = $saveHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form): ?TrickInterface
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $datas = $form->getData();

            $trick = $this->trickBuilder->build($datas)
                                        ->getTrick();

            $this->entityManager->persist($trick);

            if (!empty($datas->videos)) {
                foreach ($datas->videos as $video) {
                    $newVideo = $this->videoBuilder->build($video, $trick);

                    $this->entityManager->persist($newVideo);
                }
            }

            $pictures = [];
            if (!empty($datas->pictures)) {
                $counter = 1;
                foreach ($datas->pictures as $picture) {
                    $newPicture = $this->pictureBuilder->build($picture, $trick, $counter);
                    $pictures[] = [$picture->picture, $newPicture];

                    $this->entityManager->persist($newPicture);

                    $counter++;
                }
            }

            $this->entityManager->flush();

            if (!empty($pictures)) {
                $this->saveHelper->save($pictures);
            }

            return $trick;
        }

        return null;
    }
}
