<?php

namespace App\Domain\Builders\Interfaces;

use App\Domain\DTO\Interfaces\Trick\NewTrickDTOInterface;
use App\Domain\Model\Trick;

interface TrickBuilderInterface
{

    /**
     * @param NewTrickDTOInterface $datas
     * @return TrickBuilderInterface
     */
    public function build(NewTrickDTOInterface $datas);

    /**
     * @return Trick
     */
    public function getTrick(): Trick;
}
