<?php

namespace App\UI\Responders\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\TrickModificationPresenterInterface;
use App\UI\Responders\Interfaces\Trick\TrickModificationResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TrickModificationResponder implements TrickModificationResponderInterface {
	/**
	 * @var TrickModificationPresenterInterface
	 */
	private $presenter;
	/**
	 * @var UrlGeneratorInterface
	 */
	private $urlGenerator;

	/**
	 * TrickModificationResponder constructor.
	 *
	 * @param TrickModificationPresenterInterface $presenter
	 * @param UrlGeneratorInterface $urlGenerator
	 */
	public function __construct(
		TrickModificationPresenterInterface $presenter,
		UrlGeneratorInterface $urlGenerator
	) {
		$this->presenter = $presenter;
		$this->urlGenerator = $urlGenerator;
	}

	/**
	 * {@inheritdoc}
	 */
	public function trickModificationResponse(
		?bool $redirection = true,
		?string $redirectionURL = 'home',
		?array $parameters = [],
		?FormInterface $form = null,
		?TrickInterface $trick = null
	): Response {
		return ($redirection) ?
			new RedirectResponse($this->urlGenerator->generate($redirectionURL, $parameters)) :
			new Response(
				$this->presenter->trickModificationPresentation(
					$form,
					$trick
				)
			);
	}
}
