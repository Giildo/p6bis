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
     * @return Trick|null
     * @throws NonUniqueResultException
     */
    public function loadOneTrickWithCategoryAndAuthor(string $trickSlug): ?Trick
    {
        return $this->createQueryBuilder('trick')
            ->leftJoin('trick.videos', 'videos')
            ->leftJoin('trick.pictures', 'pictures')
            ->addSelect('videos')
            ->addSelect('pictures')
            ->where('trick.slug = :slug')
            ->setParameter('slug', $trickSlug)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
