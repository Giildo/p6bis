<?php

namespace App\UI\Actions\User;

use App\Application\Handlers\Interfaces\Forms\User\ProfilePictureHandlerInterface;
use App\Domain\DTO\Trick\PictureDTO;
use App\Domain\DTO\User\ProfilePictureDTO;
use App\Domain\Model\Interfaces\UserInterface;
use App\UI\Forms\User\ProfilePictureType;
use App\UI\Responders\Interfaces\User\ProfilePictureResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * ProfilePictureAction constructor.
     * @param ProfilePictureResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     * @param TokenStorageInterface $tokenStorage
     * @param ProfilePictureHandlerInterface $handler
     */
    public function __construct(
        ProfilePictureResponderInterface $responder,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        ProfilePictureHandlerInterface $handler
    ) {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->handler = $handler;
    }

    /**
     * @Route(path="/espace-utilisateur/utilisateur/profil",name="Profile_picture")
     *
     * @param Request $request
     *
     * @return Response
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

        return $this->responder->response($form, $user);
    }
}
