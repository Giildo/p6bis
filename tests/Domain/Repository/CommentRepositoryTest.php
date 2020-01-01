<?php

namespace App\Tests\Domain\Repository;

use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Repository\CommentRepository;
use App\Tests\Fixtures\Traits\CommentFixtures;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\ToolsException;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CommentRepositoryTest extends KernelTestCase
{
    /**
     * @var CommentRepository
     */
    private $repository;

    /**
     * @var bool
     */
    private $initialized;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    protected function setUp()
    {
        $this->constructComments();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $this->entityManager->getRepository(Comment::class);
    }

    use CommentFixtures;

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ToolsException
     */
    public function testCommentSavingAndCount()
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());

        $this->entityManager->persist($this->johnDoe);
        $this->entityManager->persist($this->janeDoe);
        $this->entityManager->persist($this->grab);
        $this->entityManager->persist($this->rotations);
        $this->entityManager->persist($this->mute);
        $this->entityManager->persist($this->r180);
        $this->entityManager->flush();

        $this->initialized = true;

        $this->repository->saveComment($this->comment1);
        $this->repository->saveComment($this->comment2);

        foreach ($this->commentsList as $comment) {
            $this->repository->saveComment($comment);
        }

        self::assertEquals(
            2,
            $this->repository->countEntries($this->mute->getSlug())
        );

        $commentId = $this->comment1->getId();
        return $commentId;
    }

    /**
     * @depends testCommentSavingAndCount
     *
     * @param string $commentId
     *
     * @throws NonUniqueResultException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function testCommentLoadingAndDeletion(string $commentId)
    {
        $commentLoaded = $this->repository->loadOneCommentWithHerId($commentId);

        self::assertInstanceOf(
            CommentInterface::class,
            $commentLoaded
        );

        $this->repository->deleteComment($commentLoaded);
        self::assertNull($this->repository->loadOneCommentWithHerId($commentId));
    }

    /**
     * @depends testCommentSavingAndCount
     */
    public function testCommentLoadingWithPagination()
    {
        self::assertEquals(
            Comment::NUMBER_OF_ITEMS,
            count(
                $this->repository->loadCommentsWithPagination(
                    $this->r180->getSlug(),
                    1
                )
            )
        );
    }

    /**
     * @depends testCommentSavingAndCount
     */
    public function testAllCommentsLoadingByOneTrick()
    {
        $comments = $this->repository->loadAllCommentsOfATrick($this->r180->getSlug());

        self::assertEquals(
            15,
            count(
                $comments
            )
        );

        return $comments[0];
    }

    /**
     * @depends testAllCommentsLoadingByOneTrick
     * @param CommentInterface $comment
     */
    public function testGettersOfCommentModel(CommentInterface $comment)
    {
        self::assertInternalType('string', $comment->getComment());
        self::assertInstanceOf(UserInterface::class, $comment->getAuthor());
        self::assertInstanceOf(Uuid::class, $comment->getId());
        self::assertInstanceOf(DateTime::class, $comment->getCreatedAt());
        self::assertInstanceOf(DateTime::class, $comment->getUpdatedAt());
        self::assertInstanceOf(TrickInterface::class, $comment->getTrick());
    }
}
