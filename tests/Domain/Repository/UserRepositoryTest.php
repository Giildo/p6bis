<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\User;
use App\Domain\Repository\UserRepository;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;

class UserRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    public function setUp()
    {
        $this->constructPicturesAndVideos();

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->userRepository = $this->entityManager->getRepository(User::class);

        $this->tokenGenerator = new UriSafeTokenGenerator();
    }

    use PictureAndVideoFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testUserSavingAndUserLoading()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->persist($this->pictureProfile);
        $this->entityManager->flush();

        $userLoaded = $this->userRepository->loadUserByUsername($this->johnDoe->getUsername());

        self::assertInstanceOf(
            UserInterface::class,
            $userLoaded
        );
    }

    /**
     * @depends testUserSavingAndUserLoading
     *
     * @return UserInterface
     *
     * @throws Exception
     */
    public function testLoadingUserByToken()
    {
        $userLoaded = $this->userRepository->loadUserByUsername($this->johnDoe->getUsername());

        $userLoaded->createToken($this->tokenGenerator);

        $this->userRepository->saveUser($userLoaded);

        $userLoadedByToken = $this->userRepository->loadUserByToken($userLoaded->getToken());

        self::assertInstanceOf(UserInterface::class, $userLoadedByToken);

        return $userLoadedByToken;
    }

    /**
     * @depends testLoadingUserByToken
     *
     * @param UserInterface $user
     */
    public function testGettersOfUserModel(UserInterface $user)
    {
        self::assertInternalType('string', $user->getUsername());
        self::assertInternalType('string', $user->getFirstName());
        self::assertInternalType('string', $user->getLastName());
        self::assertInternalType('string', $user->getMail());
        self::assertInternalType('string', $user->getPassword());
        self::assertInternalType('array', $user->getRoles());

        self::assertInstanceOf(UuidInterface::class, $user->getId());

        self::assertEquals('12345678', $user->getPassword());
        $user->changePassword('87654321');
        self::assertEquals('87654321', $user->getPassword());

        self::assertEquals(['ROLE_USER'], $user->getRoles());
        $user->addRole('ROLE_ADMIN');
        self::assertEquals(['ROLE_USER', 'ROLE_ADMIN'], $user->getRoles());
        $user->changeRole(['ROLE_OTHER']);
        self::assertEquals(['ROLE_OTHER'], $user->getRoles());

        self::assertInternalType('string', $user->getToken());
        self::assertInstanceOf(DateTime::class, $user->getTokenDate());
        $user->deleteToken();
        self::assertNull($user->getToken());
        self::assertNull($user->getTokenDate());

        self::assertInstanceOf(PictureInterface::class, $user->getPicture());
        self::assertEquals('ProfilePicture123456789', $user->getPicture()->getName());
        self::assertEquals('John', $user->getFirstName());
        self::assertEquals('Doe', $user->getLastName());
        self::assertEquals('john@doe.fr', $user->getMail());
        $user->updateProfile(
            'Robert',
            'Baratheon',
            'robert@beratheon.got',
            $this->pictureHead
        );
        self::assertEquals('HeadPicture123456789', $user->getPicture()->getName());
        self::assertEquals('Robert', $user->getFirstName());
        self::assertEquals('Baratheon', $user->getLastName());
        self::assertEquals('robert@beratheon.got', $user->getMail());
        $user->deletePicture();
        self::assertNull($user->getPicture());

        $user->eraseCredentials();
        self::assertEquals('', $user->getSalt());
    }
}
