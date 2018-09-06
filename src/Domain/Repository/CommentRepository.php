<?php

namespace App\Domain\Repository;

use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use App\Domain\Repository\Interfaces\RepositoryCounterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class CommentRepository extends ServiceEntityRepository implements RepositoryCounterInterface
{
    /**
     * CommentRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @param CommentInterface $comment
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveComment(CommentInterface $comment): void
    {
        $this->getEntityManager()->persist($comment);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string $id
     *
     * @return CommentInterface|null
     *
     * @throws NonUniqueResultException
     */
    public function loadOneCommentWithHerId(string $id): ?CommentInterface
    {
        return $this->createQueryBuilder('comment')
            ->where('comment.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param string $trickSlug
     * @param int|null $paging
     *
     * @return CommentInterface[]
     */
    public function loadCommentsWithPagination(string $trickSlug, ?int $paging = null): array
    {
        $first = ($paging - 1) * Comment::NUMBER_OF_ITEMS;

        return $this->createQueryBuilder('comment')
            ->where('comment.trick = :trickSlug')
            ->setParameter('trickSlug', $trickSlug)
            ->setMaxResults(Comment::NUMBER_OF_ITEMS)
            ->setFirstResult($first)
            ->orderBy('comment.updatedAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $trickSlug
     *
     * @return CommentInterface[]|null
     */
    public function loadAllCommentsOfATrick(string $trickSlug): ?array
    {
        return $this->createQueryBuilder('comment')
            ->where('comment.trick = :trickSlug')
            ->setParameter('trickSlug', $trickSlug)
            ->getQuery()
            ->execute();
    }

    /**
     * @param CommentInterface $comment
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteComment(CommentInterface $comment): void
    {
        $this->getEntityManager()->remove($comment);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function countEntries(?string $identifier = null): int
    {
        return (int)$this->createQueryBuilder('comment')
            ->select('count(comment.id)')
            ->where('comment.trick = :trickSlug')
            ->setParameter('trickSlug', $identifier)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
