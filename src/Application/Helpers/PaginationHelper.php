<?php

namespace App\Application\Helpers;

use App\Application\Helpers\Interfaces\PaginationHelperInterface;
use App\Domain\Repository\Interfaces\RepositoryCounterInterface;

class PaginationHelper implements PaginationHelperInterface
{
    /**
     * {@inheritdoc}
     */
    public function pagination(
        RepositoryCounterInterface $repository,
        int $numberItems,
        int $paging,
        ?string $identifier = null
    ): ?int {
        $entries = $repository->countEntries($identifier);

        $numberPage = (int)ceil($entries / $numberItems);

        if ($paging < 1 || $paging > $numberPage) {
            return null;
        }

        return $numberPage;
    }
}
