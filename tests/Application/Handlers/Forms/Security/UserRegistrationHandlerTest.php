<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\UserRegistrationHandler;
use App\Domain\Builders\UserBuilder;
use App\Domain\DTO\Security\UserRegistrationDTO;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserRegistrationHandlerTest extends TestCase
{
    /**
     * @var UserRegistrationHandler
     */
    private $handler;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    protected function setUp()
    {
        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $encoder->method('encodePassword')->willReturn('passwordEncoded');
        $builder = new UserBuilder($encoder);

        $this->repository = $this->createMock(UserRepository::class);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $dto = new UserRegistrationDTO(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678',
            true
        );
        $this->form = $this->createMock(FormInterface::class);
        $this->form->method('getData')
                   ->willReturn($dto);

        $this->handler = new UserRegistrationHandler(
            $builder,
            $this->repository,
            $eventDispatcher
        );
    }

    /**
     * @throws ORMException
     */
    public function testFalseReturnWhenFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    /**
     * @throws ORMException
     */
    public function testFalseReturnWhenFormIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    /**
     * @throws ORMException
     */
    public function testTrueReturnWhenFormIsSubmittedAndIsValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertTrue($this->handler->handle($this->form));
    }
}
