<?php

namespace App\Domain\Repository;

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
     * @param User $user
     *
     * @throws ORMException
     */
    public function saveUser(User $user)
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
     * @return User|null
     */
    public function loadUserByUsername($username): ?User
    {
        $queryBuilder = $this->createQueryBuilder('u')
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
     * @return User|null
     */
    public function loadUserByToken(string $token): ?User
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
