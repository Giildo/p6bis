<?php

namespace App\Application\Helpers\Interfaces;

use App\Domain\Repository\Interfaces\RepositoryCounterInterface;
use Doctrine\ORM\NonUniqueResultException;

interface PaginationHelperInterface
{
    /**
     * @param RepositoryCounterInterface $repository
     * @param int $numberItems
     * @param int $paging
     * @param null|string $identifier
     *
     * @return int|null
     *
     * @throws NonUniqueResultException
     */
    public function pagination(
        RepositoryCounterInterface $repository,
        int $numberItems,
        int $paging,
        ?string $identifier = null
    ): ?int ;
}
