<?php

namespace App\Domain\Modifier\Interfaces;

use App\Domain\DTO\Interfaces\Trick\TrickModificationDTOInterface;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Modifier\TrickModifier;

interface TrickModifierInterface {
	/**
	 * @param TrickInterface $trick
	 * @param TrickModificationDTOInterface $DTO
	 *
	 * @return TrickModifier
	 */
	public function modify(
		TrickInterface $trick,
		TrickModificationDTOInterface $DTO
	);

	/**
	 * @return TrickInterface
	 */
	public function getTrick(): TrickInterface;
}