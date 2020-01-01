<?php

namespace App\Tests\Application\Mailers\Security;

use App\Application\Mailers\Interfaces\Security\PasswordRecoveryMailerInterface;
use App\Application\Mailers\Security\PasswordRecoveryMailer;
use App\Tests\Fixtures\Traits\UsersFixtures;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

class PasswordRecoveryMailerTest extends KernelTestCase
{
    /**
     * @var PasswordRecoveryMailerInterface
     */
    private $recoveryMailer;

    protected function setUp()
    {
        $this->constructUsers();

        $kernel = self::bootKernel();

        $mailer = $kernel->getContainer()->get('swiftmailer.mailer.default');
        $twig = $this->createMock(Environment::class);
        $twig->method('render')->willReturn('Vue du mail');

        $this->recoveryMailer = new PasswordRecoveryMailer($mailer, $twig);
    }

    use UsersFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(PasswordRecoveryMailerInterface::class, $this->recoveryMailer);
    }

    public function testReturnMethod()
    {
        self::assertTrue($this->recoveryMailer->message($this->johnDoe));
    }
}
