<?php

namespace App\Application\Authenticator\Security;

use App\Application\Authenticator\Interfaces\Security\UserConnectionTypeAuthenticatorInterface;
use App\Application\Events\Core\FlashMessageEvent;
use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\UI\Forms\Security\UserConnectionType;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class UserConnectionTypeAuthenticator
 *
 * Class for user authentication.
 * Based on Guard Authenticator.
 *
 * @package App\Application\Authenticator\Security
 */
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
     * @param UrlGeneratorInterface $urlGenerator
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        EncoderFactoryInterface $encoderFactory,
        UserConnectionHandlerInterface $handler,
        UrlGeneratorInterface $urlGenerator,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->formFactory = $formFactory;
        $this->repository = $repository;
        $this->encoderFactory = $encoderFactory;
        $this->handler = $handler;
        $this->urlGenerator = $urlGenerator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginUrl(): string
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
     * {@inheritdoc}
     *
     * In case of errors in form, this method returns the errors to 'getUser'.
     *
     * @return FormErrorIterator|UserConnectionDTOInterface
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
     * If errors are returned since 'getCredentials' this method @uses
     * EventDispatcherInterface for adds a flash message in session.
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
            }

            return $user;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     *
     * If the password is invalid, this method @uses EventDispatcherInterface
     * for adds a flash message in session.
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
     */
    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        $providerKey
    ) {
        return new RedirectResponse(
            $this->urlGenerator->generate('Home')
        );
    }
}
