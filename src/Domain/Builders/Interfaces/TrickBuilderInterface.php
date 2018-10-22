<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Trick\NewTrickDTO;
use App\Domain\Model\Trick;

interface TrickBuilderInterface
{

    /**
     * @param NewTrickDTO $datas
     * @return TrickBuilderInterface
     */
    public function build(NewTrickDTO $datas): self;

    /**
     * @return Trick
     */
    public function getTrick(): Trick;
}
