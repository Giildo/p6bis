<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\PasswordRecoveryForUsernameHandler;
use App\Domain\DTO\Interfaces\Security\PasswordRecoveryForUsernameDTOInterface;
use App\Domain\DTO\Security\PasswordRecoveryForUsernameDTO;
use App\Domain\Repository\UserRepository;
use App\Tests\Fixtures\Traits\UsersFixtures;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class PasswordRecoveryForUsernameHandlerTest extends TestCase
{
    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var PasswordRecoveryForUsernameHandler
     */
    private $handler;

    /**
     * @var UserRepository|MockObject
     */
    private $repository;

    /**
     * @var PasswordRecoveryForUsernameDTOInterface
     */
    private $dto;

    public function setUp()
    {
        $this->constructUsers();

        $tokenGenerator = new UriSafeTokenGenerator();

        $this->form = $this->createMock(FormInterface::class);

        $this->repository = $this->createMock(UserRepository::class);

        $this->dto = new PasswordRecoveryForUsernameDTO('Username');

        $this->handler = new PasswordRecoveryForUsernameHandler($this->repository, $tokenGenerator);
    }

    use UsersFixtures;

    /**
     * @throws ORMException
     */
    public function testNullReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertNull($this->handler->handle($this->form));
    }

    /**
     * @throws ORMException
     */
    public function testNullReturnIfFormIsSubmittedButIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertNull($this->handler->handle($this->form));
    }

    /**
     * @throws ORMException
     */
    public function testNullReturnIfFormIsSubmittedAndIsValidButBadUsername()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->form->method('getData')->willReturn($this->dto);
        $this->repository->method('loadUserByUsername')->willReturn(null);

        self::assertNull($this->handler->handle($this->form));
    }

    /**
     * @throws ORMException
     */
    public function testUserReturnIfFormIsSubmittedAndIsValidAndGoodUsername()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->form->method('getData')->willReturn($this->dto);
        $this->repository->method('loadUserByUsername')->willReturn($this->johnDoe);

        $response = $this->handler->handle($this->form);

        self::assertInstanceOf(UserInterface::class, $response);
        self::assertEquals($this->johnDoe->getToken(), $response->getToken());
    }
}
