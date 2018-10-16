<?php

namespace App\Tests\Application\Handlers\Forms\Security;

use App\Application\Handlers\Forms\Security\PasswordRecoveryForPasswordHandler;
use App\Domain\DTO\Security\PasswordRecoveryForPasswordDTO;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Tests\Fixtures\Traits\UsersFixtures;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use PDO;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class PasswordRecoveryForPasswordHandlerTest extends KernelTestCase
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PasswordRecoveryForPasswordHandler
     */
    private $handler;

    /**
     * @var FormInterface|MockObject
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var PasswordRecoveryForPasswordDTO|MockObject
     */
    private $dto;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @throws Exception
     */
    public function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->constructUsers();

        $this->johnDoe->createToken(
            new UriSafeTokenGenerator()
        );

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->flush();

        $this->userRepository = $this->entityManager->getRepository(User::class);

        $encoder = $this->createMock(UserPasswordEncoderInterface::class);
        $encoder->method('encodePassword')->willReturn('passwordEncoded');

        $this->form = $this->createMock(FormInterface::class);

        $this->request = new Request();

        $this->dto = new PasswordRecoveryForPasswordDTO('123456789');

        $this->handler = new PasswordRecoveryForPasswordHandler(
            $this->userRepository,
            $encoder
        );
    }

    use UsersFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseReturnIfFormIsntSubmitted()
    {
        $this->form->method('isSubmitted')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseReturnIfFormIsSubmittedButIsntValid()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(false);

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseIfTokenIsNull()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testFalseReturnIfBadToken()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->request->query->set('ut', 'badToken');

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws Exception
     */
    public function testFalseReturnIfDateTokenHasPassed()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->request->query->set('ut', $this->johnDoe->getToken());
        $id = $this->johnDoe->getId();
        $this->entityManager->detach($this->johnDoe);

        $this->form->method('getData')->willReturn($this->dto);

        $date = (new DateTime())->sub(
            new DateInterval('PT2H')
        );
        $dbPath = __DIR__ . '/../../../../../var/data.db';
        $pdo = new PDO("sqlite:{$dbPath}");
        $prepare = $pdo->prepare("UPDATE p6bis_user SET token_date = :newDate WHERE id = :id");
        $prepare->execute([
            'newDate' => $date->format('Y-m-d H:i:s'),
            'id'      => $id,
        ]);

        self::assertFalse($this->handler->handle($this->form, $this->request));
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTrueReturnIfGoodTokenAndTokenIsDeleted()
    {
        $this->form->method('isSubmitted')->willReturn(true);
        $this->form->method('isValid')->willReturn(true);

        $this->request->query->set('ut', $this->johnDoe->getToken());

        $this->form->method('getData')->willReturn($this->dto);

        self::assertTrue($this->handler->handle($this->form, $this->request));
    }
}
