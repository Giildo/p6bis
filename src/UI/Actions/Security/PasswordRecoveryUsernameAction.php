<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\UI\Forms\Security\PasswordRecoveryForUsernameType;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use Doctrine\ORM\ORMException;
use Symfony\Component\Form\FormError;
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
 * Class PasswordRecoveryActionUsername
 * @package App\UI\Actions\Security
 */
class PasswordRecoveryUsernameAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var PasswordRecoveryForUsernameHandlerInterface
     */
    private $forUsernameHandler;
    /**
     * @var PasswordRecoveryResponderInterface
     */
    private $responder;
    /**
     * @var PasswordRecoveryMailerInterface
     */
    private $recoveryMailer;

    /**
     * PasswordRecoveryActionUsername constructor.
     * @param FormFactoryInterface $formFactory
     * @param PasswordRecoveryForUsernameHandlerInterface $forUsernameHandler
     * @param PasswordRecoveryResponderInterface $responder
     * @param PasswordRecoveryMailerInterface $recoveryMailer
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        PasswordRecoveryForUsernameHandlerInterface $forUsernameHandler,
        PasswordRecoveryResponderInterface $responder,
        PasswordRecoveryMailerInterface $recoveryMailer
    ) {
        $this->formFactory = $formFactory;
        $this->forUsernameHandler = $forUsernameHandler;
        $this->responder = $responder;
        $this->recoveryMailer = $recoveryMailer;
    }

    /**
     * @Route(path="/recuperation", name="password_recovery_username")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws ORMException
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function passwordRecovery(Request $request): Response
    {
        $form = $this->formFactory->create(
            PasswordRecoveryForUsernameType::class
        )
                                  ->handleRequest($request)
        ;

        if (is_null($user = $this->forUsernameHandler->handle($form))) {
            return $this->responder->passwordRecoveryResponse(
                false, $form, 'forUsername'
            );
        }

        if ($this->recoveryMailer->message($user)) {
            return $this->responder->passwordRecoveryResponse(
                false,
                null,
                ''
            );
        }

        $form->addError(
            new FormError(
                'Une erreur est survenue lors de l\'envoie du mail veuillez réessayer ou nous contacter.'
            )
        );

        return $this->responder->passwordRecoveryResponse(
            false, $form, 'forUsername'
        );
    }
}
