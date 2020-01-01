<?php

namespace App\Domain\Repository\Interfaces;

use Doctrine\ORM\NonUniqueResultException;

interface RepositoryCounterInterface
{
    /**
     * @param string|null $identifier
     *
     * @return int
     *
     * @throws NonUniqueResultException
     */
    public function countEntries(?string $identifier = null): int;
}
