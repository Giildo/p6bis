<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\UserRegistrationHandlerInterface;
use App\UI\Forms\Security\UserRegistrationType;
use App\UI\Responders\Interfaces\Security\UserRegistrationResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

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
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function registration(Request $request)
    {
        $form = $this->formFactory->create(UserRegistrationType::class)
                                  ->handleRequest($request)
        ;

        if ($this->handler->handle($form)) {
            return $this->responder->userRegistrationResponse();
        }

        return $this->responder->userRegistrationResponse(false, $form);
    }
}
