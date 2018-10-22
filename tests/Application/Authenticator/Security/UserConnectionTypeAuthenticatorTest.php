<?php

namespace App\Tests\Application\Authenticator\Security;

use App\Application\Authenticator\Interfaces\Security\UserConnectionTypeAuthenticatorInterface;
use App\Application\Authenticator\Security\UserConnectionTypeAuthenticator;
use App\Application\Handlers\Interfaces\Forms\Security\UserConnectionHandlerInterface;
use App\Domain\DTO\Interfaces\Security\UserConnectionDTOInterface;
use App\Domain\DTO\Security\UserConnectionDTO;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Tests\Fixtures\Traits\UsersFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormErrorIterator;
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
    /**
     * @var UserConnectionTypeAuthenticatorInterface
     */
    private $authenticator;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var UserConnectionHandlerInterface|MockObject
     */
    private $userConnectionHandler;

    /**
     * @var UserProviderInterface|MockObject
     */
    private $userProvider;

    /**
     * @var UserConnectionDTOInterface
     */
    private $userDTO;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PasswordEncoderInterface|MockObject
     */
    private $passwordEncoder;

    /**
     * @var EncoderFactoryInterface|MockObject
     */
    private $encoderFactory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @throws ToolsException
     */
    protected function setUp()
    {
        $this->constructUsers();

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->userRepository = $this->entityManager->getRepository(User::class);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->userDTO = new UserConnectionDTO('JohnDoe', '12345678');
        $formInterface = $this->createMock(FormInterface::class);
        $formInterface->method('handleRequest')->willReturnSelf();
        $formInterface->method('getData')->willReturn($this->userDTO);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($formInterface);

        $this->passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $this->encoderFactory = $this->createMock(EncoderFactoryInterface::class);

        $this->userConnectionHandler = $this->createMock(
            UserConnectionHandlerInterface::class
        );

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->method('generate')->willReturn('/connexion');

        $attributes = $this->createMock(ParameterBag::class);
        $this->request = new Request();
        $this->request->attributes = $attributes;

        $this->eventDispatcher = new EventDispatcher();

        $this->authenticator = new UserConnectionTypeAuthenticator(
            $formFactory, $this->userRepository, $this->encoderFactory,
            $this->userConnectionHandler, $urlGenerator, $this->eventDispatcher
        );

        $this->userProvider = $this->createMock(UserProviderInterface::class);
    }

    use UsersFixtures;

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

    public function testTheReturnOfGetUserMethodIfCredentialsIsError()
    {
        $credentials = new FormErrorIterator(
            $this->createMock(FormInterface::class),
            [new FormError('error 1'), new FormError('error 2')]
        );

        $response = $this->authenticator->getUser($credentials, $this->userProvider);

        self::assertNull($response);
    }

    public function testTheReturnOfGetUserMethodIfTheReturnOfCredentialIsDTO()
    {
        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->flush();

        $credentials = $this->userDTO;

        $response = $this->authenticator->getUser($credentials, $this->userProvider);

        self::assertInstanceOf(UserInterface::class, $response);
    }

    public function testTheReturnOfCheckCredentialsMethodIfCredentialsIsEmpty()
    {
        $credentials = [];

        $response = $this->authenticator->checkCredentials($credentials, $this->johnDoe);

        self::assertFalse($response);
    }

    public function testTheReturnOfCheckCredentialsMethodIfCredentialsIsGoodAndPasswordNotValid()
    {
        $credentials = $this->userDTO;

        $this->passwordEncoder->method('isPasswordValid')->willReturn(false);
        $this->encoderFactory->method('getEncoder')->willReturn($this->passwordEncoder);

        $response = $this->authenticator->checkCredentials($credentials, $this->johnDoe);

        self::assertFalse($response);
    }

    public function testTheReturnOfCheckCredentialsMethodIfCredentialsIsGoodAndPasswordIsValid()
    {
        $credentials = $this->userDTO;

        $this->passwordEncoder->method('isPasswordValid')->willReturn(true);
        $this->encoderFactory->method('getEncoder')->willReturn($this->passwordEncoder);

        $response = $this->authenticator->checkCredentials($credentials, $this->johnDoe);

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
