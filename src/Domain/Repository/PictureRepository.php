<?php

namespace App\Domain\Repository;

use App\Domain\Model\Interfaces\PictureInterface;
use App\Domain\Model\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class PictureRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Picture::class);
	}

	/**
	 * @param string $name
	 *
     * @return PictureInterface|null
     *
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function loadOnePictureWithName(string $name): ?PictureInterface
	{
		return $this->createQueryBuilder('p')
			->where('p.name = :name')
			->setParameter('name', $name)
			->getQuery()
			->getOneOrNullResult();
	}

    /**
     * @param PictureInterface $picture
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deletePicture(PictureInterface $picture): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->remove($picture);
        $entityManager->flush();
	}
}
