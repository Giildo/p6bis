<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Trick;
use Symfony\Component\Form\FormInterface;

interface TrickModificationPresenterInterface {
	/**
	 * @param FormInterface $form
	 * @param Trick $trick
	 *
	 * @return string
	 *
	 */
	public function trickModificationPresentation(
		FormInterface $form,
		Trick $trick
	): string;
}