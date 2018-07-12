<?php

namespace App\Tests\UI\Presenters\Security;

use App\UI\Forms\Security\UserConnectionType;
use App\UI\Forms\Security\UserRegistrationType;
use App\UI\Presenters\Interfaces\Security\UserRegistrationPresenterInterface;
use App\UI\Presenters\Security\UserRegistrationPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRegistrationPresenterTest extends KernelTestCase
{
    private $presenter;

    private $formFactory;

    /**
     *
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $twig = $kernel->getContainer()->get('twig');
        $this->formFactory = $kernel->getContainer()->get('form.factory');

        $this->presenter = new UserRegistrationPresenter($twig);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(UserRegistrationPresenterInterface::class, $this->presenter);
    }

    public function testReturnOfPresentation()
    {
        $form = $this->formFactory->create(UserRegistrationType::class);

        $response = $this->presenter->userRegistrationPresentation($form);

        self::assertInternalType('string', $response);
    }
}
