<?php

namespace App\Domain\Modifier;

use App\Domain\DTO\Trick\TrickModificationDTO;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Modifier\Interfaces\TrickModifierInterface;

class TrickModifier implements TrickModifierInterface {
	/**
	 * @var TrickInterface
	 */
	private $trick;

	/**
	 * {@inheritdoc}
	 */
	public function modify(
		TrickInterface $trick,
        TrickModificationDTO $DTO
	): TrickModifierInterface {
		$this->trick = $trick->update(
			$DTO->description,
			$DTO->published,
			$DTO->category
		);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTrick(): TrickInterface
	{
		return $this->trick;
	}
}
