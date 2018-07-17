<?php

namespace App\Tests\Application\Authenticator\Security;

use App\Application\Authenticator\Interfaces\Security\UserConnectionTypeAuthenticatorInterface;
use App\Application\Authenticator\Security\UserConnectionTypeAuthenticator;
use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\Domain\DTO\Security\UserConnectionDTO;
use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use App\UI\Presenters\Security\UserConnectionPresenter;
use App\UI\Responders\Security\UserConnectionResponder;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserConnectionTypeAuthenticatorTest extends KernelTestCase
{
    private $authenticator;

    private $request;

    private $userConnectionHandler;

    private $userProvider;

    private $userDTO;

    private $repository;

    private $user;

    protected function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(User::class);
        $this->user = $this->repository->loadUserByUsername('JohnDoe');

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../../fixtures/user_registration/02.specific_user.yml', $entityManager);

        $this->userDTO = new UserConnectionDTO('JohnDoe', '12345678');
        $formInterface = $this->createMock(FormInterface::class);
        $formInterface->method('handleRequest')->willReturnSelf();
        $formInterface->method('getData')->willReturn($this->userDTO);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($formInterface);

        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder->method('isPasswordValid')->willReturn(true);
        $encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $encoderFactory->method('getEncoder')->willReturn($passwordEncoder);

        $this->userConnectionHandler = $this->createMock(
            UserConnectionHandlerInterface::class
        );

        $presenter = $this->createMock(UserConnectionPresenter::class);
        $userConnectionResponder = new UserConnectionResponder($presenter);

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/connexion');

        $attributes = $this->createMock(ParameterBag::class);
        $this->request = new Request();
        $this->request->attributes = $attributes;

        $this->authenticator = new UserConnectionTypeAuthenticator(
            $formFactory,
            $this->repository,
            $encoderFactory,
            $this->userConnectionHandler,
            $userConnectionResponder,
            $urlGenerator
        );

        $this->userProvider = $this->createMock(UserProviderInterface::class);
    }

    use LoadFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(
            UserConnectionTypeAuthenticatorInterface::class,
            $this->authenticator
        );
    }

    public function testTheReturnOfGetLoginUrlMethod()
    {
        $response = $this->authenticator->getLoginUrl();

        self::assertInternalType('string', $response);
    }

    public function testTheReturnIsFalseOfSupportsMethodIfRouteIsntGood()
    {
        $this->request->attributes->method('get')->willReturn('Mauvaise route');

        $response = $this->authenticator->supports($this->request);

        self::assertFalse($response);
    }

    public function testTheReturnIsFalseOfSupportsMethodIfRouteIsGoodAndMethodIsntGood()
    {
        $this->request->attributes->method('get')->willReturn('Authentication_user_connection');
        $this->request->setMethod('GET');

        $response = $this->authenticator->supports($this->request);

        self::assertFalse($response);
    }

    public function testTheReturnIsTrueOfSupportsMethodIfRouteIsGoodAndMethodIsGood()
    {
        $this->request->attributes->method('get')->willReturn('Authentication_user_connection');
        $this->request->setMethod('POST');

        $response = $this->authenticator->supports($this->request);

        self::assertTrue($response);
    }

    public function testTheReturnOfGetCredentialsMethodIfHandlerReturnFalse()
    {
        $this->userConnectionHandler->method('handle')->willReturn(false);

        $response = $this->authenticator->getCredentials($this->request);

        self::assertEmpty($response);
    }

    public function testTheReturnOfGetCredentialsMethodIfHandlerReturnTrue()
    {
        $this->userConnectionHandler->method('handle')->willReturn(true);

        $response = $this->authenticator->getCredentials($this->request);

        self::assertInstanceOf(UserConnectionDTOInterface::class, $response);
    }

    public function testTheReturnOfGetUserMethodIfTheReturnOfCredentialIsEmpty()
    {
        $credentials = [];

        $response = $this->authenticator->getUser($credentials, $this->userProvider);

        self::assertNull($response);
    }

    public function testTheReturnOfGetUserMethodIfTheUsernameIsFalse()
    {
        $this->userDTO->username = 'Pseudo';

        $credentials = $this->userDTO;

        $response = $this->authenticator->getUser($credentials, $this->userProvider);

        self::assertNull($response);
    }

    public function testTheReturnOfGetUserMethodIfTheReturnOfCredentialIsDTO()
    {
        $credentials = $this->userDTO;

        $response = $this->authenticator->getUser($credentials, $this->userProvider);

        self::assertInstanceOf(UserInterface::class, $response);
    }

    public function testTheReturnOfCheckCredentialsMethodIfCredentialsIsEmpty()
    {
        $credentials = [];

        $response = $this->authenticator->checkCredentials($credentials, $this->user);

        self::assertFalse($response);
    }

    public function testTheReturnOfCheckCredentialsMethodIfCredentialsIsGood()
    {
        $credentials = $this->userDTO;

        $response = $this->authenticator->checkCredentials($credentials, $this->user);

        self::assertTrue($response);
    }

    public function testTheReturnOfOnAuthenticationSuccessMethod()
    {
        $token = $this->createMock(TokenInterface::class);
        $provider = '12345678';

        $response = $this->authenticator->onAuthenticationSuccess(
            $this->request,
            $token,
            $provider
        );

        self::assertInstanceOf(RedirectResponse::class, $response);
    }
}
