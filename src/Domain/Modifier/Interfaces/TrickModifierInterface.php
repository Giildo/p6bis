<?php

namespace App\Domain\Modifier\Interfaces;

use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Modifier\TrickModifier;

interface TrickModifierInterface {
    /**
     * @param TrickInterface $trick
     * @param TrickModificationDTO $DTO
     *
     * @return TrickModifier
     */
	public function modify(
		TrickInterface $trick,
		TrickModificationDTO $DTO
    ):self;

    /**
	 * @return TrickInterface
	 */
	public function getTrick(): TrickInterface;
}