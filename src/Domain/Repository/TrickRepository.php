<?php

namespace App\Domain\Repository;

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
     * @return Trick[]
     */
    public function loadAllTricksWithAuthorCategoryAndHeadPicture()
    {
        return $this->createQueryBuilder('t')
            ->where('t.published = 1')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $trickSlug
     * @return Trick
     * @throws NonUniqueResultException
     */
    public function loadOneTrickWithCategoryAndAuthor(string $trickSlug)
    {
        return $this->createQueryBuilder('trick')
            ->innerJoin('trick.videos', 'videos')
            ->innerJoin('trick.pictures', 'pictures')
            ->where('pictures.trick = :slug')
            ->andWhere('videos.trick = :slug')
            ->andWhere('trick.slug = :slug')
            ->setParameter('slug', $trickSlug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
