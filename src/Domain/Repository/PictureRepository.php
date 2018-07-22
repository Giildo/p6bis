<?php

namespace App\Domain\Repository;

use App\Domain\Model\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function loadPictureByTrick(string $trickSlug)
    {
        return $this->createQueryBuilder('picture')
                    ->innerJoin('picture.trick', 'trick')
                    ->where('trick.slug = :slug')
                    ->setParameter('slug', $trickSlug)
                    ->getQuery()
                    ->getResult();
    }
}
