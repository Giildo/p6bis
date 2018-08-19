<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TrickModificationDeletePictureAction
{
    /**
     * @var TrickModificationDeleteVideoOrPictureResponderInterface
     */
    private $responder;
	/**
	 * @var PictureRepository
	 */
	private $pictureRepository;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TrickModificationDeletePictureAction constructor.
     *
     * @param TrickModificationDeleteVideoOrPictureResponderInterface $responder
     * @param PictureRepository $pictureRepository
     * @param SessionInterface $session
     */
	public function __construct(
	    TrickModificationDeleteVideoOrPictureResponderInterface $responder,
        PictureRepository $pictureRepository,
        SessionInterface $session
	) {
        $this->responder = $responder;
		$this->pictureRepository = $pictureRepository;
        $this->session = $session;
    }

    /**
     * @Route(path="/espace-utilisateur/trick/image-suppression", name="Trick_modification_delete_picture")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function deletePicture(Request $request): RedirectResponse
	{
		$pictureName = $request->query->get('s');
		$token = $request->query->get('t');

		/** @var Picture $picture */
		$picture = $this->pictureRepository->loadOnePictureWithName($pictureName);
		$trickSlug = $picture->getTrick()->getSlug();
		$pictureExtension = $picture->getExtension();

		$tokens = $this->session->get('tokens');

		if ($token === $tokens[$picture->getName()]) {
			$this->pictureRepository->deletePicture($picture);
		}

		unlink(__DIR__ . "/../../../../public/pic/tricks/{$pictureName}.{$pictureExtension}");

		return $this->responder->response($trickSlug);
	}
}
