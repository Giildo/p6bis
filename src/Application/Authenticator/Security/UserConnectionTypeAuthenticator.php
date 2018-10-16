<?php

namespace App\Application\Authenticator\Security;

use App\Application\Authenticator\Interfaces\Security\UserConnectionTypeAuthenticatorInterface;
use App\Application\Events\Core\FlashMessageEvent;
use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\UI\Forms\Security\UserConnectionType;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class UserConnectionTypeAuthenticator extends AbstractFormLoginAuthenticator
    implements UserConnectionTypeAuthenticatorInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;
    /**
     * @var UserConnectionHandlerInterface
     */
    private $handler;
    /**
     * @var UserConnectionResponderInterface
     */
    private $responder;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * UserConnectionTypeAuthenticator constructor.
     * @param FormFactoryInterface $formFactory
     * @param UserRepository $repository
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserConnectionHandlerInterface $handler
     * @param UserConnectionResponderInterface $responder
     * @param UrlGeneratorInterface $urlGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        EncoderFactoryInterface $encoderFactory,
        UserConnectionHandlerInterface $handler,
        UserConnectionResponderInterface $responder,
        UrlGeneratorInterface $urlGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->formFactory = $formFactory;
        $this->repository = $repository;
        $this->encoderFactory = $encoderFactory;
        $this->handler = $handler;
        $this->responder = $responder;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginUrl()
    {
        return $this->urlGenerator->generate('Authentication_user_connection');
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request): bool
    {
        return $request->attributes->get(
            '_route'
        ) === 'Authentication_user_connection' &&
        $request->isMethod('POST') ?
            true :
            false;
    }

    /**
     * {@inheritdoc=
     */
    public function getCredentials(Request $request)
    {
        $form = $this->formFactory->create(UserConnectionType::class)
                                  ->handleRequest($request)
        ;

        if ($this->handler->handle($form)) {
            return $form->getData();
        }

        return $form->getErrors(true, true);
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonUniqueResultException
     */
    public function getUser(
        $credentials,
        UserProviderInterface $userProvider
    ) {
        if ($credentials instanceof FormErrorIterator) {
            foreach ($credentials as $credential) {
                $flashMessage = new FlashMessageEvent(
                    false,
                    $credential->getMessage()
                );

                $this->eventDispatcher->dispatch(
                    FlashMessageEvent::FLASH_MESSAGE,
                    $flashMessage
                );
            }

            return null;
        }

        if (!empty($credentials)) {
            $user = $this->repository->loadUserByUsername(
                $credentials->username
            );

            if (is_null($user)) {
                $flashMessage = new FlashMessageEvent(
                    false,
                    'Le nom d\'utilisateur n\'existe pas.'
                );

                $this->eventDispatcher->dispatch(
                    FlashMessageEvent::FLASH_MESSAGE,
                    $flashMessage
                );

                return null;
            }

            return $user;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials(
        $credentials,
        UserInterface $user
    ) {
        if (!empty($credentials)) {
            $encoder = $this->encoderFactory->getEncoder(User::class);

            $passwordValid = $encoder->isPasswordValid(
                $user->getPassword(),
                $credentials->password,
                ''
            );

            if (!$passwordValid) {
                $flashMessage = new FlashMessageEvent(
                    false,
                    'Le mot de passe est invalide.'
                );

                $this->eventDispatcher->dispatch(
                    FlashMessageEvent::FLASH_MESSAGE,
                    $flashMessage
                );
            }

            return $passwordValid;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        return $this->responder->userConnectionResponse();
    }
}
