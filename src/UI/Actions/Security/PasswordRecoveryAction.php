<?php

namespace App\UI\Actions\Security;

use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\UI\Forms\Security\PasswordRecoveryForPasswordType;
use App\UI\Forms\Security\PasswordRecoveryForUsernameType;
use App\UI\Responders\Interfaces\Security\PasswordRecoveryResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(name="Authentication_")
 *
 * Class PasswordRecoveryAction
 * @package App\UI\Actions\Security
 */
class PasswordRecoveryAction
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
     * @var PasswordRecoveryForUsernameHandlerInterface
     */
    private $forUsernameHandler;
    /**
     * @var PasswordRecoveryForPasswordHandlerInterface
     */
    private $forPasswordHandler;
    /**
     * @var PasswordRecoveryResponderInterface
     */
    private $responder;
    /**
     * @var PasswordRecoveryMailerInterface
     */
    private $recoveryMailer;

    /**
     * PasswordRecoveryAction constructor.
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param FormFactoryInterface $formFactory
     * @param PasswordRecoveryForUsernameHandlerInterface $forUsernameHandler
     * @param PasswordRecoveryForPasswordHandlerInterface $forPasswordHandler
     * @param PasswordRecoveryResponderInterface $responder
     * @param PasswordRecoveryMailerInterface $recoveryMailer
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        FormFactoryInterface $formFactory,
        PasswordRecoveryForUsernameHandlerInterface $forUsernameHandler,
        PasswordRecoveryForPasswordHandlerInterface $forPasswordHandler,
        PasswordRecoveryResponderInterface $responder,
        PasswordRecoveryMailerInterface $recoveryMailer
    )
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->formFactory = $formFactory;
        $this->forUsernameHandler = $forUsernameHandler;
        $this->forPasswordHandler = $forPasswordHandler;
        $this->responder = $responder;
        $this->recoveryMailer = $recoveryMailer;
    }

    /**
     * @Route(path="/recuperation", name="password_recovery")
     *
     * @param Request $request
     *
     * @param FlashBagInterface $flashBag
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordRecovery(Request $request, FlashBagInterface $flashBag): Response
    {
        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            return $this->responder->passwordRecoveryResponse();
        }

        if (is_null($request->query->get('ut'))) {
            $form = $this->formFactory->create(PasswordRecoveryForUsernameType::class)
                ->handleRequest($request);

            $user = $this->forUsernameHandler->handle($form);

            if (is_null($user)) {
                return $this->responder->passwordRecoveryResponse(false, $form, 'forUsername');
            }

            if ($this->recoveryMailer->message($user->getMail())) {
                return $this->responder->passwordRecoveryResponse(false, null, '');
            }

            $form->addError(new FormError(
                'Une erreur est survenue lors de l\'envoie du mail veuillez réessayer ou nous contacter.'
            ));

            return $this->responder->passwordRecoveryResponse(false, $form, 'forUsername');
        }

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
