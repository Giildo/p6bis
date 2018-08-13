<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationDeletePictureAction
{
    /**
     * @var TrickModificationDeleteVideoOrPictureResponderInterface
     */
    private $responder;
	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;
	/**
	 * @var PictureRepository
	 */
	private $repository;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TrickModificationDeletePictureAction constructor.
     *
     * @param TrickModificationDeleteVideoOrPictureResponderInterface $responder
     * @param EntityManagerInterface $entityManager
     * @param SessionInterface $session
     */
	public function __construct(
	    TrickModificationDeleteVideoOrPictureResponderInterface $responder,
		EntityManagerInterface $entityManager,
        SessionInterface $session
	) {
        $this->responder = $responder;
		$this->entityManager = $entityManager;
		$this->repository = $entityManager->getRepository(Picture::class);
        $this->session = $session;
    }

	/**
	 * @Route(path="/espace-utilisateur/trick/image-suppression", name="Trick_modification_delete_picture")
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

		$tokens = $this->session->get('tokens');

		if ($token === $tokens[$picture->getName()]) {
			$this->entityManager->remove($picture);
			$this->entityManager->flush();
		}

		unlink(__DIR__ . "/../../../../public/pic/tricks/{$pictureName}.{$pictureExtension}");

		return $this->responder->response($trickSlug);
	}
}
