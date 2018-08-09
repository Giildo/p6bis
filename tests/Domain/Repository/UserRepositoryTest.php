<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class UserRepositoryTest extends KernelTestCase
{
    private $repository;

    private $tokenGenerator;

    public function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(User::class);

        $this->tokenGenerator = new UriSafeTokenGenerator();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../fixtures/user/00.load.yml', $entityManager);
    }

    use LoadFixtures;

    public function testSavingAndLoadingUserIntoTheDatabaseIfUserIsUnique()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $this->repository->saveUser($user);
        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertEquals($user, $userLoaded);
    }

    public function testSavingAndLoadingUserIntoTheDatabaseIfUserIsDouble()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $user2 = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $this->repository->saveUser($user);
        $this->repository->saveUser($user2);
        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertNull($userLoaded);
    }

    public function testLoadingUserIfUserIsntInDatabase()
    {
        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertNull($userLoaded);
    }

    public function testLoadingUserByToken()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $user->createToken($this->tokenGenerator);
        $token = $user->getToken();

        $this->repository->saveUser($user);

        $userLoaded = $this->repository->loadUserByToken($token);

        self::assertEquals($user, $userLoaded);
    }

    public function testLoadingUserByTokenIfUserIsntIntoDatabase()
    {
        $userLoaded = $this->repository->loadUserByToken('token_5sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        self::assertNull($userLoaded);
    }

    public function testLoadingUserByTokenIfUserIsDouble()
    {
        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generateToken')
                       ->willReturn('token_5sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $user2 = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $user->createToken($tokenGenerator);
        $user2->createToken($tokenGenerator);

        $this->repository->saveUser($user);
        $this->repository->saveUser($user2);
        $userLoaded = $this->repository->loadUserByToken('token_5sEUfrS9W3bcsCJzEyUlyLDTg6Dn1Ul3xF0EQ');

        self::assertNull($userLoaded);
    }
}
