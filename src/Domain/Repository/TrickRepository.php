<?php

namespace App\Domain\Repository;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @return TrickInterface[]
     */
    public function loadAllTricksWithAuthorCategoryAndHeadPicture(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.published = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $trickSlug
     * @return TrickInterface|null
     * @throws NonUniqueResultException
     */
    public function loadOneTrickWithCategoryAndAuthor(string $trickSlug): ?TrickInterface
    {
        return $this->createQueryBuilder('trick')
            ->leftJoin('trick.videos', 'videos')
            ->leftJoin('trick.pictures', 'pictures')
            ->leftJoin('trick.comments', 'comments')
            ->addSelect('videos')
            ->addSelect('pictures')
            ->addSelect('comments')
            ->where('trick.slug = :slug')
            ->setParameter('slug', $trickSlug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
