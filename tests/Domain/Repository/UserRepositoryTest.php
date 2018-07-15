<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\User;
use App\Tests\fixtures\LoadFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
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
    }

    use LoadFixtures;

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public function testSaveUserFormRegistrationToUserRepository()
    {
        $user = new User(
            'JohnDoe',
            'John',
            'Doe',
            'john.doe@gmail.com',
            '12345678'
        );

        $repository = $this->entityManager->getRepository(User::class);
        $repository->saveUserFromRegistration($user);
        $userLoaded = $repository->findAll();

        self::assertEquals($user, array_pop($userLoaded));
    }
}
