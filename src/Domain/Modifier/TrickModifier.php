<?php

namespace App\Domain\Modifier;

use App\Domain\DTO\Interfaces\Trick\TrickModificationDTOInterface;
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
		TrickModificationDTOInterface $DTO
	): self {
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
