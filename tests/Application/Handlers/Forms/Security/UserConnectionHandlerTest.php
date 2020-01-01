<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\UserConnectionHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormInterface;

class UserConnectionHandlerTest extends TestCase
{
    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var UserConnectionHandler
     */
    private $handler;

    protected function setUp()
    {
        $this->form = $this->createMock(FormInterface::class);

        $this->handler = new UserConnectionHandler();
    }

    public function testHandlerIfFormIsNotSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    public function testHandlerIfFormIsSubmittedAndNotValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form));
    }

    public function testHandlerIfFormIsSubmittedAndIsValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertTrue($this->handler->handle($this->form));
    }
}
