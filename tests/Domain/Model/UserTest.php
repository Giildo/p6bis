<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class UserTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $user;

    private $userLoaded;

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp()
    {
        $kernel = static::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../fixtures/user_registration/00.load.yml', $this->entityManager);

        $this->user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $this->entityManager->persist($this->user);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(User::class);
        $users = $repository->findAll();
        $this->userLoaded = array_pop($users);
    }

    use LoadFixtures;

    public function testConstructor()
    {
        self::assertInstanceOf(User::class, $this->user);
    }

    public function testConstructorAndGetters()
    {
        self::assertInstanceOf(UuidInterface::class, $this->userLoaded->getId());
        self::assertEquals('JohnDoe', $this->userLoaded->getUsername());
        self::assertEquals('John', $this->userLoaded->getFirstName());
        self::assertEquals('Doe', $this->userLoaded->getLastName());
        self::assertEquals('john.doe@gmail.com', $this->userLoaded->getMail());
        self::assertEquals('12345678', $this->userLoaded->getPassword());
        self::assertEquals('', $this->userLoaded->getSalt());
        self::assertEquals(['ROLE_USER'], $this->userLoaded->getRoles());
        self::assertNull($this->userLoaded->eraseCredentials());
    }

    public function testAttributesType()
    {
        self::assertAttributeInternalType('string', 'username', $this->userLoaded);
        self::assertAttributeInternalType('string', 'firstName', $this->userLoaded);
        self::assertAttributeInternalType('string', 'lastName', $this->userLoaded);
        self::assertAttributeInternalType('string', 'mail', $this->userLoaded);
        self::assertAttributeInternalType('string', 'password', $this->userLoaded);
    }

    public function testCreationAndDeletionOfToken()
    {
        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generateToken')
                       ->willReturn('8_Me185sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $this->user->createToken($tokenGenerator);

        self::assertInternalType('string', $this->user->getToken());
        self::assertInstanceOf(DateTime::class, $this->user->getTokenDate());

        $this->user->deleteToken();

        self::assertNull($this->user->getToken());
        self::assertNull($this->user->getTokenDate());
    }

    public function testChangementOfPassword()
    {
        $this->user->changePassword('87654321');

        self::assertEquals('87654321', $this->user->getPassword());
    }

    public function testRolesChangements()
    {
        self::assertEquals(['ROLE_USER'], $this->user->getRoles());

        $this->user->changeRole(['ROLE_ADMIN']);

        self::assertEquals(['ROLE_ADMIN'], $this->user->getRoles());

        $this->user->addRole('ROLE_USER');

        self::assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $this->user->getRoles());
    }
}
