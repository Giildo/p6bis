<?php

namespace App\Domain\Repository;

use App\Domain\Model\Comment;
use App\Domain\Model\Interfaces\CommentInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class CommentRepository extends ServiceEntityRepository
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
}
