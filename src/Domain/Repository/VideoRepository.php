<?php

namespace App\Domain\Repository;

use App\Domain\Model\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function loadVideoByTrick(string $trickSlug)
    {
        return $this->createQueryBuilder('video')
                    ->innerJoin('video.trick', 'trick')
                    ->where('trick.slug = :slug')
                    ->setParameter('slug', $trickSlug)
                    ->getQuery()
                    ->getResult();
    }
}
