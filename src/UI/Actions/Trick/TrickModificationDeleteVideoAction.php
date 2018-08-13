<?php

namespace App\UI\Actions\Trick;

use App\Domain\Model\Video;
use App\Domain\Repository\VideoRepository;
use App\UI\Responders\Interfaces\Trick\TrickModificationDeleteVideoOrPictureResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
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
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var VideoRepository
     */
    private $repository;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * TrickModificationDeleteVideoAction constructor.
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
        $this->repository = $entityManager->getRepository(Video::class);
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
     */
    public function deleteVideo(Request $request): RedirectResponse
    {
        $videoName = $request->query->get('s');
        $token = $request->query->get('t');

        $video = $this->repository->loadOneVideoWithName($videoName);
        $trickSlug = $video->getTrick()->getSlug();

        $tokens = $this->session->get('tokens');

        if ($token === $tokens[$videoName]) {
            $this->entityManager->remove($video);
            $this->entityManager->flush();
        }

        return $this->responder->response($trickSlug);
    }
}
