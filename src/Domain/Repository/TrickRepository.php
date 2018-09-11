<?php

namespace App\Domain\Repository;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\Domain\Repository\Interfaces\RepositoryCounterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TrickRepository extends ServiceEntityRepository implements RepositoryCounterInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * @param int $paging
     *
     * @return TrickInterface[]
     */
    public function loadTricksWithPaging(int $paging): array
    {
        $first = ($paging - 1) * Trick::NUMBER_OF_ITEMS;

        return $this->createQueryBuilder('trick')
            ->orderBy('trick.createdAt')
            ->where('trick.published = 1')
            ->setFirstResult($first)
            ->setMaxResults(Trick::NUMBER_OF_ITEMS)
            ->orderBy('trick.updatedAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $trickSlug
     *
     * @return TrickInterface|null
     *
     * @throws NonUniqueResultException
     */
    public function loadOneTrickWithCategoryAndAuthor(string $trickSlug): ?TrickInterface
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

    /**
     * @param TrickInterface $trick
     * @param array $comments
     *
     * @return void
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteTrick(
        TrickInterface $trick,
        array $comments
    ): void {
        $entityManager = $this->getEntityManager();

        $pictures = $trick->getPictures();
        $picturesName = [];
        if (!empty($pictures)) {
            foreach ($pictures as $picture) {
                $picturesName[] = "{$picture->getName()}.{$picture->getExtension()}";
                $entityManager->remove($picture);
            }
        }

        $videos = $trick->getVideos();
        if (!empty($videos)) {
            foreach ($videos as $video) {
                $entityManager->remove($video);
            }
        }

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $entityManager->remove($comment);
            }
        }

        $entityManager->remove($trick);
        $entityManager->flush();

        if (!empty($picturesName)) {
            foreach ($picturesName as $name) {
                unlink(__DIR__ . '/../../../public/pic/tricks/' . $name);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function countEntries(?string $identifier = null): int
    {
        return (int)$this->createQueryBuilder('trick')
            ->select('count(trick.slug)')
            ->where('trick.published = 1')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
