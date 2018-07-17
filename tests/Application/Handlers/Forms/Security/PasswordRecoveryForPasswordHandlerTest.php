<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\PasswordRecoveryForPasswordHandler;
use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForPasswordHandlerInterface;
use App\Domain\DTO\Security\PasswordRecoveryForPasswordDTO;
use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordRecoveryForPasswordHandlerTest extends KernelTestCase
{
    private $form;

    private $request;

    private $handler;

    private $repository;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $doctrine = $kernel->getContainer()->get('doctrine');
        $this->repository = $doctrine->getRepository(User::class);
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $this->form = $this->createMock(FormInterface::class);

        $this->request = new Request();

        $encoder = $this->createMock(PasswordEncoderInterface::class);
        $encoder->method('encodePassword')
                ->willReturn('$2y$10$HTXhRibKaaSTNsj2xvXfQO1fDkLODML0NGn9ds/.Z/l7bRHTbV83K');
        $encoderFactory = $this->createMock(EncoderFactoryInterface::class);
        $encoderFactory->method('getEncoder')->willReturn($encoder);

        $this->handler = new PasswordRecoveryForPasswordHandler($this->repository, $encoderFactory);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../../../fixtures/user_registration/02.specific_user.yml', $entityManager);

        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generateToken')
                       ->willReturn('8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');
        $user = $this->repository->loadUserByUsername('JohnDoe');
        $user->createToken($tokenGenerator);
        $this->repository->saveUser($user);
    }

    use LoadFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryForPasswordHandlerInterface::class, $this->handler);
    }

    public function testFalseReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->request);

        self::assertFalse($response);
    }

    public function testFalseReturnIfFormIsSubmittedButIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form, $this->request);

        self::assertFalse($response);
    }

    public function testFalseReturnIfBadToken()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->request->query->set('ut', 'token_5sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $response = $this->handler->handle($this->form, $this->request);

        self::assertFalse($response);
    }

    public function testTrueReturnIfGoodTokenAndTokenIsDeleted()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->request->query->set('ut', '8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $dto = new PasswordRecoveryForPasswordDTO('87654321');
        $this->form->method('getData')->willReturn($dto);

        $response = $this->handler->handle($this->form, $this->request);

        self::assertTrue($response);

        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertNull($userLoaded->getToken());
    }
}
