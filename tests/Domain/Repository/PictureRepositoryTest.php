<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use App\Domain\Repository\PictureRepository;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PictureRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var PictureRepository
     */
    private $repository;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ToolsException
     */
    protected function setUp()
    {
        $this->constructPicturesAndVideos();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $this->entityManager->getRepository(Picture::class);

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->flush();
        $this->entityManager->persist($this->pictureProfile);
        $this->entityManager->flush();
    }

    use PictureAndVideoFixtures;

    /**
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function testPictureLoadingWithHisName()
    {
        $pictureLoaded = $this->repository->loadOnePictureWithName('ProfilePicture123456789');

        self::assertInstanceOf(PictureInterface::class, $pictureLoaded);

        return $pictureLoaded;
    }

    /**
     * @depends testPictureLoadingWithHisName
     *
     * @param PictureInterface $picture
     */
    public function testGettersOfPicturesModel(PictureInterface $picture)
    {
        self::assertInternalType('string', $picture->getName());
        self::assertInternalType('string', $picture->getExtension());
        self::assertInternalType('string', $picture->getDescription());
        self::assertInternalType('bool', $picture->isHeadPicture());
        self::assertNull($picture->getTrick());

        self::assertNull($picture->getDeleteToken());
        $picture->createToken('token123456789');
        self::assertEquals('token123456789', $picture->getDeleteToken());

        self::assertEquals('Description de la photo de profil.', $picture->getDescription());
        self::assertFalse($picture->isHeadPicture());
        $picture->update('Nouvelle description', true);
        self::assertEquals('Nouvelle description', $picture->getDescription());
        self::assertTrue($picture->isHeadPicture());
    }

    /**
     * @param PictureInterface $pictureLoaded
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testPictureDeletion()
    {
        $this->repository->deletePicture($this->pictureProfile, $this->johnDoe);

        self::assertNull(
            $this->repository->loadOnePictureWithName('ProfilePicture123456789')
        );
    }
}
