<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeletePictureAction
{
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var PictureRepository
	 */
	private $repository;
	/**
	 * @var UrlGeneratorInterface
	 */
	private $urlGenerator;

	/**
	 * TrickModificationDeletePictureAction constructor.
	 *
	 * @param EntityManagerInterface $entityManager
	 * @param UrlGeneratorInterface $urlGenerator
	 */
	public function __construct(
		EntityManagerInterface $entityManager,
		UrlGeneratorInterface $urlGenerator
	) {
		$this->entityManager = $entityManager;
		$this->repository = $entityManager->getRepository(Picture::class);
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * @Route(name="trick_modification_delete_picture", path="/espace-utilisateur/trick/image-suppression")
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function deletePicture(Request $request): RedirectResponse
	{
		$pictureName = $request->query->get('s');
		$token = $request->query->get('t');
		/** @var Picture $picture */
		$picture = $this->repository->loadOnePictureWithName($pictureName);
		$trickSlug = $picture->getTrick()->getSlug();
		$pictureExtension = $picture->getExtension();

		if ($token === $picture->getDeleteToken()) {
			$this->entityManager->remove($picture);
			$this->entityManager->flush();
		}

		unlink(__DIR__ . "/../../../../public/pic/tricks/{$pictureName}.{$pictureExtension}");

		return new RedirectResponse($this->urlGenerator->generate(
			'trick_modification',
			['trickSlug' => $trickSlug]
		));
	}
}
