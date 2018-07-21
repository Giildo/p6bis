<?php

namespace App\UI\Actions\Security;

use App\UI\Forms\Security\UserConnectionType;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(name="Authentication_")
 *
 * Class UserConnectionAction
 * @package App\UI\Actions\Security
 */
class UserConnectionAction
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var UserConnectionResponderInterface
     */
    private $responder;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * UserConnectionAction constructor.
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param UserConnectionResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        UserConnectionResponderInterface $responder,
        FormFactoryInterface $formFactory
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->responder = $responder;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route(path="/connexion", name="user_connection")
     *
     * @param Request $request
     * @param AuthenticationUtils $utils
     *
     * @return Response
     */
    public function connection(Request $request, AuthenticationUtils $utils): Response
    {
        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            return $this->responder->userConnectionResponse();
        }

        $errors = $utils->getLastAuthenticationError();

        $lastUserConnected = $utils->getLastUsername();

        $form = $this->formFactory->create(UserConnectionType::class)
                                  ->handleRequest($request);

        return $this->responder->userConnectionResponse(
            false,
            $form,
            $errors,
            $lastUserConnected
        );
    }
}
