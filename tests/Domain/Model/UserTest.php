<?php

namespace App\Tests\Domain\Model;

use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function setUp() {
        $kernel = static::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../fixtures/user_registration/00.load.yml', $this->entityManager);
    }

    use LoadFixtures;

    public function testConstructorAndGetters()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        self::assertInstanceOf(User::class, $user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(User::class);
        $users = $repository->findAll();
        /** @var User $userLoaded */
        $userLoaded = array_pop($users);

        self::assertInstanceOf(UuidInterface::class, $user->getId());
        self::assertEquals('JohnDoe', $userLoaded->getUsername());
        self::assertEquals('John', $userLoaded->getFirstName());
        self::assertEquals('Doe', $userLoaded->getLastName());
        self::assertEquals('john.doe@gmail.com', $userLoaded->getMail());
        self::assertEquals('12345678', $userLoaded->getPassword());
        self::assertEquals('', $userLoaded->getSalt());
        self::assertEquals(['ROLE_USER'], $userLoaded->getRoles());
        self::assertNull($user->eraseCredentials());
    }
}
