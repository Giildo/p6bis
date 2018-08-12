<?php

namespace App\Domain\DTO\Trick;

use App\Domain\DTO\Interfaces\Trick\TrickModificationPictureDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class TrickModificationPictureDTO implements TrickModificationPictureDTOInterface {
	/**
	 * @var string
	 */
	public $description;

	/**
	 * @var bool
	 */
	public $headPicture;

	/**
	 * TrickModificationPictureDTO constructor.
	 *
	 * @param string $description
	 * @param bool $headPicture
	 */
	public function __construct(
		?string $description = '',
		?bool $headPicture = false
	) {
		$this->description = $description;
		$this->headPicture = $headPicture;
	}
}
