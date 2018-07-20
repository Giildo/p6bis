<?php

namespace App\Domain\Repository;

use App\Domain\Model\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function loadAllTricksWithAuthorCategoryAndHeadPicture()
    {
        return $this->createQueryBuilder('t')
            ->where('t.published = 1')
            ->getQuery()
            ->getResult();
    }
}
