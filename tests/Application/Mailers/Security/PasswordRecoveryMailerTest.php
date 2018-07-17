<?php

namespace App\Tests\Application\Mailers\Security;

use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\Application\Mailers\Security\PasswordRecoveryMailer;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

class PasswordRecoveryMailerTest extends KernelTestCase
{
    private $recoveryMailer;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $mailer = $kernel->getContainer()->get('swiftmailer.mailer.default');
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('Vue du mail');

        $this->recoveryMailer = new PasswordRecoveryMailer($mailer, $twig);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryMailerInterface::class, $this->recoveryMailer);
    }

    public function testReturnMethod()
    {
        self::assertTrue($this->recoveryMailer->message('john.doe@gmail.com'));
    }
}
