<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\UI\Forms\Security\PasswordRecoveryForPasswordType;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(name="Authentication_")
 *
 * Class PasswordRecoveryActionUsername
 * @package App\UI\Actions\Security
 */
class PasswordRecoveryPasswordAction
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var PasswordRecoveryForPasswordHandlerInterface
     */
    private $forPasswordHandler;
    /**
     * @var PasswordRecoveryResponderInterface
     */
    private $responder;

    /**
     * PasswordRecoveryActionUsername constructor.
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param FormFactoryInterface $formFactory
     * @param PasswordRecoveryForPasswordHandlerInterface $forPasswordHandler
     * @param PasswordRecoveryResponderInterface $responder
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        PasswordRecoveryForPasswordHandlerInterface $forPasswordHandler,
        PasswordRecoveryResponderInterface $responder
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->forPasswordHandler = $forPasswordHandler;
        $this->responder = $responder;
    }

    /**
     * @Route(path="/recuperation/mot-de-passe", name="password_recovery_password")
     *
     * @param Request $request
     *
     * @param FlashBagInterface $flashBag
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordRecovery(Request $request, FlashBagInterface $flashBag): Response
    {
        $form = $this->formFactory->create(PasswordRecoveryForPasswordType::class)
                                  ->handleRequest($request);

        if ($this->forPasswordHandler->handle($form, $request)) {
            $flashBag->add(
                'passwordRecovery',
                'Votre mot de passe a bien été modifié, veuillez vous connecter.'
            );
            return $this->responder->passwordRecoveryResponse();
        }

        return $this->responder->passwordRecoveryResponse(false, $form, 'forPassword');
    }
}
