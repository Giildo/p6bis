<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CategoryInterface;
use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TrickRepositoryTest extends KernelTestCase
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function setUp()
    {
        $this->constructComments();

        $kernel = static::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->trickRepository = $this->entityManager->getRepository(Trick::class);
        $this->commentRepository = $this->entityManager->getRepository(Comment::class);
    }

    use CommentFixtures;

    /**
     * @throws ToolsException
     */
    public function testTrickLoading()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->persist($this->grab);
        $this->entityManager->flush();
        $this->entityManager->persist($this->mute);
        $this->entityManager->flush();
        $this->entityManager->persist($this->pictureNoHead);
        $this->entityManager->persist($this->videoTrick);
        $this->entityManager->persist($this->comment1);
        $this->entityManager->persist($this->comment2);

        foreach ($this->tricksList as $trick) {
            $this->entityManager->persist($trick);
        }

        $this->entityManager->flush();

        $tricks = $this->trickRepository->loadTricksWithPaging(1);

        self::assertInstanceOf(TrickInterface::class, $tricks[0]);
        self::assertInstanceOf(UserInterface::class, $tricks[0]->getAuthor());
        self::assertInstanceOf(CategoryInterface::class, $tricks[0]->getCategory());
        self::assertEquals(
            Trick::NUMBER_OF_ITEMS,
            count(
                $tricks
            )
        );

        return $tricks[0];
    }

    /**
     * @depends testTrickLoading
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function testOfOneTrickLoading()
    {
        $trickLoaded = $this->trickRepository->loadOneTrickWithCategoryAndAuthor($this->mute->getSlug());

        self::assertInstanceOf(TrickInterface::class, $trickLoaded);

        return $trickLoaded;
    }

    /**
     * @depends testOfOneTrickLoading
     *
     * @param TrickInterface $trick
     */
    public function testGettersOfTrickModel(TrickInterface $trick)
    {
        self::assertInternalType('string', $trick->getSlug());
        self::assertInternalType('string', $trick->getName());
        self::assertInternalType('string', $trick->getDescription());
        self::assertInternalType('bool', $trick->isPublished());

        self::assertInstanceOf(DateTime::class, $trick->getCreatedAt());
        self::assertInstanceOf(DateTime::class, $trick->getUpdatedAt());

        self::assertInstanceOf(CategoryInterface::class, $trick->getCategory());
        self::assertInstanceOf(UserInterface::class, $trick->getAuthor());
        self::assertInstanceOf(PictureInterface::class, $trick->getPictures()[0]);
        self::assertInstanceOf(VideoInterface::class, $trick->getVideos()[0]);

        self::assertEquals('Description de la figure', $trick->getDescription());
        self::assertEquals('Grab', $trick->getCategory()->getName());
        $trick->update(
            'Nouvelle description',
            false,
            $this->rotations
        );
        self::assertEquals('Nouvelle description', $trick->getDescription());
        self::assertEquals('rotations', $trick->getCategory()->getSlug());

        self::assertFalse($trick->isPublished());
        $trick->publish();
        self::assertTrue($trick->isPublished());
    }

    /**
     * @depends testGettersOfTrickModel
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testTrickDeletion()
    {
        $trickLoaded = $this->trickRepository->loadOneTrickWithCategoryAndAuthor($this->mute->getSlug());

        $file = fopen(__DIR__ . '/../../../public/pic/tricks/NoHeadPicture123456789.jpeg', 'w+');
        fclose($file);

        $comments = $this->commentRepository->loadAllCommentsOfATrick($this->mute->getSlug());

        $this->trickRepository->deleteTrick($trickLoaded, $comments);

        $trickLoaded = $this->trickRepository->loadOneTrickWithCategoryAndAuthor($this->mute->getSlug());

        self::assertNull($trickLoaded);
    }

    /**
     * @depends testTrickDeletion
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     */
    public function testCountTrick()
    {
        self::assertEquals(
            15,
            $this->trickRepository->countEntries()
        );
    }
}
