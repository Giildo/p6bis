<?php

namespace App\Tests\UI\Presenters\Security;

use App\UI\Forms\Security\PasswordRecoveryForPasswordType;
use App\UI\Forms\Security\PasswordRecoveryForUsernameType;
use App\UI\Presenters\Security\PasswordRecoveryPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PasswordRecoveryPresenterTest extends KernelTestCase
{
    private $formFactory;

    private $presenter;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');
        $this->formFactory = $kernel->getContainer()->get('form.factory');

        $this->presenter = new PasswordRecoveryPresenter($twig);
    }

    public function testTheReturnWithFormByUsername()
    {
        $form = $this->formFactory->create(PasswordRecoveryForUsernameType::class);

        $response = $this->presenter->passwordRecoveryPresentation($form, 'forPassword');

        self::assertInternalType('string', $response);
    }

    public function testTheReturnWithFormByPassword()
    {
        $form = $this->formFactory->create(PasswordRecoveryForPasswordType::class);

        $response = $this->presenter->passwordRecoveryPresentation($form, 'forUsername');

        self::assertInternalType('string', $response);
    }

    public function testTheReturnWithOptionsIsMailerMessage()
    {
        $response = $this->presenter->passwordRecoveryPresentation(null, '', true);

        self::assertInternalType('string', $response);
    }

    public function testNullIfNoFormAndNoMailerSuccess()
    {
        self::assertNull($this->presenter->passwordRecoveryPresentation());
    }
}
