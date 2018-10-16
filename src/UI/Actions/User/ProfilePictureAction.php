<?php

namespace App\UI\Actions\User;

use App\Application\Handlers\Interfaces\Forms\User\ProfilePictureHandlerInterface;
use App\Domain\DTO\Trick\PictureDTO;
use App\Domain\DTO\User\ProfilePictureDTO;
use App\Domain\Model\Interfaces\UserInterface;
use App\UI\Forms\User\ProfilePictureType;
use App\UI\Responders\Interfaces\User\ProfilePictureResponderInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ProfilePictureAction
{
    /**
     * @var ProfilePictureResponderInterface
     */
    private $responder;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var ProfilePictureHandlerInterface
     */
    private $handler;
    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    /**
     * ProfilePictureAction constructor.
     * @param ProfilePictureResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     * @param TokenStorageInterface $tokenStorage
     * @param ProfilePictureHandlerInterface $handler
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function __construct(
        ProfilePictureResponderInterface $responder,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        ProfilePictureHandlerInterface $handler,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->handler = $handler;
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @Route(path="/espace-utilisateur/utilisateur/profil",name="Profile_picture")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function profilePicture(Request $request): Response
    {
        /** @var UserInterface $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $dto = new ProfilePictureDTO(
            $user->getFirstName(),
            $user->getLastName(),
            $user->getMail(),
            new PictureDTO()
        );

        $form = $this->formFactory->create(ProfilePictureType::class, $dto)
                                  ->handleRequest($request);

        $this->handler->handle($form, $user);

        if (!is_null($picture = $user->getPicture())) {
            $picture->createToken($this->tokenGenerator->generateToken());

            $request->getSession()->set('tokenProfilePicture', $picture->getDeleteToken());
        }

        return $this->responder->response($form, $user);
    }
}
