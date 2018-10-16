<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Video;
use App\Domain\Repository\VideoRepository;
use App\Tests\Fixtures\Traits\PictureAndVideoFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class VideoRepositoryTest extends KernelTestCase
{
    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function setUp()
    {
        $this->constructPicturesAndVideos();

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->videoRepository = $this->entityManager->getRepository(Video::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws ToolsException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testVideoLoading()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->persist($this->grab);
        $this->entityManager->flush();
        $this->entityManager->persist($this->mute);
        $this->entityManager->persist($this->videoTrick);
        $this->entityManager->flush();

        $videoLoaded = $this->videoRepository->loadOneVideoWithName('jXM-2FvU0f0');

        self::assertInstanceOf(VideoInterface::class, $videoLoaded);

        return $videoLoaded;
    }

    /**
     * @depends testVideoLoading
     *
     * @param VideoInterface $video
     */
    public function testGettersOfVideoModel(VideoInterface $video)
    {
        self::assertInternalType('string', $video->getName());
        self::assertInstanceOf(TrickInterface::class, $video->getTrick());

        self::assertNull($video->getDeleteToken());
        $video->createToken('token123456789');
        self::assertEquals('token123456789', $video->getDeleteToken());
    }

    /**
     * @depends testGettersOfVideoModel
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testVideoDeletion()
    {
        $videoLoaded = $this->videoRepository->loadOneVideoWithName('jXM-2FvU0f0');

        $this->videoRepository->deleteVideo($videoLoaded);

        self::assertNull(
            $this->videoRepository->loadOneVideoWithName('jXM-2FvU0f0')
        );
    }

    use PictureAndVideoFixtures;
}
