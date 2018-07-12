<?php

namespace App\Application\Authenticator\Security;

use App\Application\Authenticator\Interfaces\Security\UserConnectionTypeAuthenticatorInterface;
use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\UI\Forms\Security\UserConnectionType;
use App\UI\Responders\Interfaces\Security\UserConnectionResponderInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class UserConnectionTypeAuthenticator extends AbstractFormLoginAuthenticator implements UserConnectionTypeAuthenticatorInterface
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
     * UserConnectionTypeAuthenticator constructor.
     * @param FormFactoryInterface $formFactory
     * @param UserRepository $repository
     * @param EncoderFactoryInterface $encoderFactory
     * @param UserConnectionHandlerInterface $handler
     * @param UserConnectionResponderInterface $responder
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UserRepository $repository,
        EncoderFactoryInterface $encoderFactory,
        UserConnectionHandlerInterface $handler,
        UserConnectionResponderInterface $responder,
        UrlGeneratorInterface $urlGenerator
    )
    {
        $this->formFactory = $formFactory;
        $this->repository = $repository;
        $this->encoderFactory = $encoderFactory;
        $this->handler = $handler;
        $this->responder = $responder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Return the URL to the login page.
     *
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->urlGenerator->generate('Authentication_user_connection');
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns false, the authenticator will be skipped.
     *
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'Authentication_user_connection' &&
        $request->isMethod('POST') ?
            true :
            false;
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array).
     *
     * Whatever value you return here will be passed to getUser() and checkCredentials()
     *
     * For example, for a form login, you might:
     *
     *      return array(
     *          'username' => $request->request->get('_username'),
     *          'password' => $request->request->get('_password'),
     *      );
     *
     * Or for an API token that's on a header, you might use:
     *
     *      return array('api_key' => $request->headers->get('X-API-TOKEN'));
     *
     * @param Request $request
     *
     * @return mixed Any non-null value
     *
     * @throws \UnexpectedValueException If null is returned
     */
    public function getCredentials(Request $request)
    {
        $form = $this->formFactory->create(UserConnectionType::class)
                                  ->handleRequest($request);

        if ($this->handler->handle($form)) {
            return $form->getData();
        }

        return [];
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!empty($credentials)) {
            return $this->repository->loadUserByUsername($credentials->username);
        }

        return null;
    }

    /**
     * Returns true if the credentials are valid.
     *
     * If any value other than true is returned, authentication will
     * fail. You may also throw an AuthenticationException if you wish
     * to cause authentication to fail.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!empty($credentials)) {
            $encoder = $this->encoderFactory->getEncoder(User::class);

            return $encoder->isPasswordValid(
                $user->getPassword(),
                $credentials->password,
                ''
            );
        }

        return false;
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey The provider (i.e. firewall) key
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->responder->userConnectionResponse();
    }
}
