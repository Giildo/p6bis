<?php

namespace App\UI\Actions\Trick;

use App\Domain\Repository\PictureRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * TrickModificationDeletePictureAction constructor.
     *
     * @param TrickModificationDeleteVideoOrPictureResponderInterface $responder
     * @param PictureRepository $pictureRepository
     */
    public function __construct(
        TrickModificationDeleteVideoOrPictureResponderInterface $responder,
        PictureRepository $pictureRepository
    ) {
        $this->responder = $responder;
        $this->pictureRepository = $pictureRepository;
    }

    /**
     * @Route(path="/espace-utilisateur/trick/image-suppression", name="Trick_modification_delete_picture")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletePicture(Request $request): RedirectResponse
    {
        $pictureName = $request->query->get('s');
        $token = $request->query->get('t');

        $picture = $this->pictureRepository->loadOnePictureWithName(
            $pictureName
        );
        $trickSlug = $picture->getTrick()
                             ->getSlug()
        ;
        $pictureExtension = $picture->getExtension();

        $tokens = $request->getSession()
                          ->remove('tokens')
        ;

        if ($token === $tokens[$picture->getName()]) {
            $this->pictureRepository->deletePicture($picture);
        }

        unlink(
            __DIR__ . "/../../../../public/pic/tricks/{$pictureName}.{$pictureExtension}"
        );

        return $this->responder->response($trickSlug);
    }
}
