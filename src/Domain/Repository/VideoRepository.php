<?php

namespace App\Domain\Repository;

use App\Domain\Model\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class VideoRepository extends ServiceEntityRepository
{
    /**
     * VideoRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    /**
     * @param string $videoName
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadOneVideoWithName(string $videoName)
    {
        return $this->createQueryBuilder('v')
            ->where('v.name = :name')
            ->setParameter('name', $videoName)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
