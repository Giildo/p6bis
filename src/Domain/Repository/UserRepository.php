<?php

namespace App\Domain\Repository;

use App\Domain\Model\Interfaces\UserInterface;
use App\Domain\Model\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param UserInterface $user
     *
     * @return void
     *
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser(UserInterface $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->leftJoin('u.picture', 'picture')
            ->addSelect('picture')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery();

        try {
            $user = $queryBuilder->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return null;
        }

        return $user;
    }

    /**
     * @param string $token
     * @return UserInterface|null
     */
    public function loadUserByToken(string $token): ?UserInterface
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->where('u.token = :token')
            ->setParameter('token', $token)
            ->getQuery();

        try {
            $user = $queryBuilder->getSingleResult();
        } catch (NoResultException | NonUniqueResultException $e) {
            return null;
        }

        return $user;
    }
}
