<?php

namespace App\Application\Handlers\Forms\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\TrickModificationHandlerInterface;
use App\Application\Helpers\Interfaces\PictureSaveHelperInterface;
use App\Domain\Builders\Interfaces\PictureBuilderInterface;
use App\Domain\Builders\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Picture;
use App\Domain\Model\Trick;
use App\Domain\Modifier\Interfaces\TrickModifierInterface;
use App\Domain\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class TrickModificationHandler implements TrickModificationHandlerInterface {
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var TrickRepository
	 */
	private $repository;
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
		$this->repository = $entityManager->getRepository(Trick::class);
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
		string $trickSlug
	): bool {
		if ($form->isSubmitted() && $form->isValid()) {
			$trick = $this->repository->loadOneTrickWithCategoryAndAuthor($trickSlug);

			if (is_null($trick)) {
				return false;
			}

			/** @var TrickModificationDTO $dto */
			$dto = $form->getData();

			/** @var Trick $trick */
			$trick = $this->trickModifier->modify($trick, $dto)
										 ->getTrick();
			$this->entityManager->persist($trick);

			$oldPictures = $trick->getPictures();
			if (!empty($oldPictures)) {
				/** @var Picture $oldPicture */
				foreach ( $oldPictures as $oldPicture ) {
					$oldPicture->deleteToken();
				}
			}

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
