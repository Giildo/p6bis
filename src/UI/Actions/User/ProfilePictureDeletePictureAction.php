<?php

namespace App\UI\Actions\User;

use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Repository\PictureRepository;
use App\UI\Responders\Interfaces\User\ProfilePictureDeletePictureResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ProfilePictureDeletePictureAction constructor.
     * @param ProfilePictureDeletePictureResponderInterface $responder
     * @param PictureRepository $pictureRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ProfilePictureDeletePictureResponderInterface $responder,
        PictureRepository $pictureRepository,
        TokenStorageInterface $tokenStorage
    ) {
        $this->responder = $responder;
        $this->pictureRepository = $pictureRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route(path="/espace-utilisateur/utilisateur/profil/suppression-photo", name="Profile_picture_delete_picture")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function deletePicture(Request $request): Response
    {
        $pictureName = $request->query->get('s');
        $token = $request->query->get('t');
        $tokenSession = $request->getSession()
                                ->remove('tokenProfilePicture')
        ;

        if ($token === $tokenSession) {
            $picture = $this->pictureRepository->loadOnePictureWithName(
                $pictureName
            );
            $namePicture = "{$picture->getName()}.{$picture->getExtension()}";

            /** @var UserInterface $user */
            $user = $this->tokenStorage->getToken()
                                       ->getUser()
            ;
            $user->deletePicture();

            $this->pictureRepository->deletePicture($picture, $user);

            unlink(__DIR__ . "/../../../../public/pic/users/{$namePicture}");
        }

        return $this->responder->response();
    }
}
