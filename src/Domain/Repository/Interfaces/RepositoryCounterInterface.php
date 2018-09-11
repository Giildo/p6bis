<?php

namespace App\Domain\Repository\Interfaces;

interface RepositoryCounterInterface
{
    /**
     * @param string|null $identifier
     *
     * @return int
     */
    public function countEntries(?string $identifier = null): int;
}
