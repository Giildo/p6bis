<?php

namespace App\UI\Actions\Trick;

use App\Domain\Repository\VideoRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickModificationDeleteVideoAction
{
    /**
     * @var TrickModificationDeleteVideoOrPictureResponderInterface
     */
    private $responder;
    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * TrickModificationDeleteVideoAction constructor.
     * @param TrickModificationDeleteVideoOrPictureResponderInterface $responder
     * @param VideoRepository $videoRepository
     */
    public function __construct(
        TrickModificationDeleteVideoOrPictureResponderInterface $responder,
        VideoRepository $videoRepository
    ) {
        $this->responder = $responder;
        $this->videoRepository = $videoRepository;
    }

    /**
     * @Route(path="/espace-utilisateur/trick/video-suppression", name="Trick_modification_delete_video")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteVideo(Request $request): RedirectResponse
    {
        $videoName = $request->query->get('s');
        $token = $request->query->get('t');

        $video = $this->videoRepository->loadOneVideoWithName($videoName);
        $trickSlug = $video->getTrick()
                           ->getSlug()
        ;

        $tokens = $request->getSession()
                          ->remove('tokens')
        ;

        if ($token === $tokens[$videoName]) {
            $this->videoRepository->deleteVideo($video);
        }

        return $this->responder->response($trickSlug);
    }
}
