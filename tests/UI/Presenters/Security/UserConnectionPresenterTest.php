<?php

namespace App\Tests\UI\Presenters\Security;

use App\UI\Forms\Security\UserConnectionType;
use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use App\UI\Presenters\Security\UserConnectionPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserConnectionPresenterTest extends KernelTestCase
{
    private $presenter;

    private $formFactory;

    private $form;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $twig = $kernel->getContainer()->get('twig');
        $this->formFactory = $kernel->getContainer()->get('form.factory');

        $this->presenter = new UserConnectionPresenter($twig);

        $this->form = $this->formFactory->create(UserConnectionType::class);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserConnectionPresenterInterface::class, $this->presenter);
    }

    public function testReturnOfPresentationWithNoError()
    {
        $error = null;

        $lastUserLoaded = '';

        $response = $this->presenter->userConnectionPresentation(
            $this->form,
            $error,
            $lastUserLoaded
        );

        self::assertInternalType('string', $response);
    }

    public function testReturnOfPresentationWithError()
    {
        $error = $this->createMock(AuthenticationException::class);
        $error->method('getMessageKey')->willReturn('Message d\'erreur.');
        $error->method('getMessageData')->willReturn([]);

        $lastUserLoaded = 'JohnDoe';

        $response = $this->presenter->userConnectionPresentation(
            $this->form,
            $error,
            $lastUserLoaded
        );

        self::assertInternalType('string', $response);
    }
}
