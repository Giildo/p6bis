<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\PasswordRecoveryForUsernameHandler;
use App\Application\Handlers\Interfaces\Forms\Security\PasswordRecoveryForUsernameHandlerInterface;
use App\Domain\DTO\Security\PasswordRecoveryForUsernameDTO;
use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordRecoveryForUsernameHandlerTest extends KernelTestCase
{
    private $form;

    private $handler;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $doctrine = $kernel->getContainer()->get('doctrine');
        $repository = $doctrine->getRepository(User::class);
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generateToken')
                       ->willReturn('8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $this->form = $this->createMock(FormInterface::class);

        $this->handler = new PasswordRecoveryForUsernameHandler($repository, $tokenGenerator);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../../../fixtures/user_registration/02.specific_user.yml', $entityManager);
    }

    use LoadFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryForUsernameHandlerInterface::class, $this->handler);
    }

    public function testNullReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        $response = $this->handler->handle($this->form);

        self::assertNull($response);
    }

    public function testNullReturnIfFormIsSubmittedButIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        $response = $this->handler->handle($this->form);

        self::assertNull($response);
    }

    public function testNullReturnIfFormIsSubmittedAndIsValidButBadUsername()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $dto = new PasswordRecoveryForUsernameDTO('Mauvais username');
        $this->form->method('getData')->willReturn($dto);

        $response = $this->handler->handle($this->form);

        self::assertNull($response);
    }

    public function testUserReturnIfFormIsSubmittedAndIsValidAndGoodUsername()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $dto = new PasswordRecoveryForUsernameDTO('JohnDoe');
        $this->form->method('getData')->willReturn($dto);

        $response = $this->handler->handle($this->form);

        self::assertInstanceOf(UserInterface::class, $response);
        self::assertEquals('8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ', $response->getToken());
    }
}
