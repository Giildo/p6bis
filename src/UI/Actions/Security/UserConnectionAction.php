<?php

namespace App\UI\Actions\Security;

use App\UI\Forms\Security\UserConnectionType;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

/**
 * @Route(name="Authentication_")
 *
 * Class UserConnectionAction
 * @package App\UI\Actions\Security
 */
class UserConnectionAction
{
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
     * @param UserConnectionResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        UserConnectionResponderInterface $responder,
        FormFactoryInterface $formFactory
    ) {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
    }

    /**
     * @Route(path="/connexion", name="user_connection")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function connection(Request $request): Response
    {
        $form = $this->formFactory->create(UserConnectionType::class)
                                  ->handleRequest($request)
        ;

        return $this->responder->userConnectionResponse(
            $form
        );
    }
}
