<?php

namespace App\Domain\Repository;

use App\Domain\Model\Interfaces\VideoInterface;
use App\Domain\Model\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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
     * @return VideoInterface|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadOneVideoWithName(string $videoName): ?VideoInterface
    {
        return $this->createQueryBuilder('v')
            ->where('v.name = :name')
            ->setParameter('name', $videoName)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param VideoInterface $video
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteVideo(VideoInterface $video): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($video);
        $entityManager->flush();
    }
}
