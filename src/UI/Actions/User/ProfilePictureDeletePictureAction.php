<?php

namespace App\UI\Actions\User;

use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Repository\PictureRepository;
use App\UI\Responders\Interfaces\User\ProfilePictureDeletePictureResponderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ProfilePictureDeletePictureAction
{
    /**
     * @var ProfilePictureDeletePictureResponderInterface
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
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProfilePictureDeletePictureAction constructor.
     * @param ProfilePictureDeletePictureResponderInterface $responder
     * @param PictureRepository $pictureRepository
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ProfilePictureDeletePictureResponderInterface $responder,
        PictureRepository $pictureRepository,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager
    ) {
        $this->responder = $responder;
        $this->pictureRepository = $pictureRepository;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/espace-utilisateur/utilisateur/profil/suppression-photo", name="Profile_picture_delete_picture")
     * @param Request $request
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deletePicture(Request $request): Response
    {
        $pictureName = $request->query->get('s');
        $token = $request->query->get('t');
        $tokenSession = $this->session->get('tokenProfilePicture');

        if ($token === $tokenSession) {
            $picture = $this->pictureRepository->loadOnePictureWithName($pictureName);
            $namePicture = "{$picture->getName()}.{$picture->getExtension()}";

            /** @var UserInterface $user */
            $user = $this->tokenStorage->getToken()->getUser();
            $user->deletePicture();

            $this->entityManager->persist($user);
            $this->entityManager->remove($picture);
            $this->entityManager->flush();

            unlink(__DIR__ . "/../../../../public/pic/users/{$namePicture}");
        }

        return $this->responder->response();
    }
}
