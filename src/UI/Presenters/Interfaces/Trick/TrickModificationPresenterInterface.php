<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;

interface TrickModificationPresenterInterface {
    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return string
     */
	public function trickModificationPresentation(
		FormInterface $form,
		TrickInterface $trick
	): string;
}