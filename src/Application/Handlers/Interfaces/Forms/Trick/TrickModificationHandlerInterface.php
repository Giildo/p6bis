<?php

namespace App\Application\Handlers\Interfaces\Forms\Trick;

use Symfony\Component\Form\FormInterface;

interface TrickModificationHandlerInterface {
	/**
	 * @param FormInterface $form
	 * @param string $trickSlug
	 *
	 * @return bool
	 *
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function handle( FormInterface $form, string $trickSlug ): bool;
}