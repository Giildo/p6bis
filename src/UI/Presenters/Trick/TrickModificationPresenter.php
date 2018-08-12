<?php

namespace App\UI\Presenters\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\TrickModificationPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class TrickModificationPresenter implements TrickModificationPresenterInterface {
	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * TrickModificationPresenter constructor.
	 *
	 * @param Environment $twig
	 */
	public function __construct(Environment $twig) {
		$this->twig = $twig;
	}

	/**
	 * {@inheritdoc}
	 */
	public function trickModificationPresentation(
		FormInterface $form,
		Trick $trick
	): string {
		return $this->twig->render('Trick/modification.html.twig', [
			'form'  => $form->createView(),
			'trick' => $trick
		]);
	}
}
