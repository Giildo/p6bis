<?php

namespace App\UI\Actions\Trick;

use App\Domain\Repository\VideoRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
     * @var SessionInterface
     */
    private $session;

    /**
     * TrickModificationDeleteVideoAction constructor.
     * @param TrickModificationDeleteVideoOrPictureResponderInterface $responder
     * @param VideoRepository $videoRepository
     * @param SessionInterface $session
     */
    public function __construct(
        TrickModificationDeleteVideoOrPictureResponderInterface $responder,
        VideoRepository $videoRepository,
        SessionInterface $session
    ) {
        $this->responder = $responder;
        $this->videoRepository = $videoRepository;
        $this->session = $session;
    }

    /**
     * @Route(path="/espace-utilisateur/trick/video-suppression", name="Trick_modification_delete_video")
     *
     * @param Request $request
     *
     * @return RedirectResponse
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteVideo(Request $request): RedirectResponse
    {
        $videoName = $request->query->get('s');
        $token = $request->query->get('t');

        $video = $this->videoRepository->loadOneVideoWithName($videoName);
        $trickSlug = $video->getTrick()->getSlug();

        $tokens = $this->session->get('tokens');

        if ($token === $tokens[$videoName]) {
            $this->videoRepository->deleteVideo($video);
        }

        return $this->responder->response($trickSlug);
    }
}
