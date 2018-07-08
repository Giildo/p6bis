<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Forms\Security\UserRegistrationType;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * UserRegistrationAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param UserRegistrationHandlerInterface $handler
     * @param UserRegistrationResponderInterface $responder
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UserRegistrationHandlerInterface $handler,
        UserRegistrationResponderInterface $responder
    ) {
        $this->formFactory = $formFactory;
        $this->handler = $handler;
        $this->responder = $responder;
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
        $form = $this->formFactory->create(UserRegistrationType::class)
                                  ->handleRequest($request);

        if ($this->handler->handle($form)) {

            return $this->responder->userRegistrationResponse();
        }

        return $this->responder->userRegistrationResponse(false, $form);
    }
}
