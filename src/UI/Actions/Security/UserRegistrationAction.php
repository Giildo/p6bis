<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Forms\Security\UserConnectionType;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(name="Authentication_")
 *
 * Class UserRegistrationAction
 * @package App\UI\Actions\Security
 */
class UserRegistrationAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UserRegistrationHandlerInterface
     */
    private $handler;
    /**
     * @var UserRegistrationResponderInterface
     */
    private $responder;
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * UserRegistrationAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param UserRegistrationHandlerInterface $handler
     * @param UserRegistrationResponderInterface $responder
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UserRegistrationHandlerInterface $handler,
        UserRegistrationResponderInterface $responder,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->responder = $responder;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Route(
     *     path="/enregistrement",
     *     name="user_registration"
     * )
     *
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function registration(Request $request)
    {
        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            return $this->responder->userRegistrationResponse();
        }

        $form = $this->formFactory->create(UserConnectionType::class)
                                  ->handleRequest($request);

        if ($this->handler->handle($form)) {
            return $this->responder->userRegistrationResponse();
        }

        return $this->responder->userRegistrationResponse(false, $form);
    }
}
