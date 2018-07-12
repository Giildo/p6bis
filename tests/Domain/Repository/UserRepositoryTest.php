<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $repository;

    public function setUp()
    {
        $kernel = static::bootKernel();
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $entityManager->getRepository(User::class);

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropSchema($entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($entityManager->getMetadataFactory()->getAllMetadata());

        $this->loadFixtures(__DIR__ . '/../../fixtures/user_registration/00.load.yml', $entityManager);
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

        $this->repository->saveUserFromRegistration($user);
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

        $this->repository->saveUserFromRegistration($user);
        $this->repository->saveUserFromRegistration($user2);
        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertNull($userLoaded);
    }

    public function testLoadingUserIfUserIsntInDatabase()
    {
        $userLoaded = $this->repository->loadUserByUsername('JohnDoe');

        self::assertNull($userLoaded);
    }
}
