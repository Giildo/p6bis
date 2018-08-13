<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface TrickModificationResponderInterface {
    /**
     * @param bool|null $redirection
     * @param null|string $redirectionURL
     * @param array|null $parameters
     * @param null|FormInterface $form
     * @param TrickInterface|null $trick
     *
     * @return Response
     */
	public function trickModificationResponse(
		?bool $redirection = true,
		?string $redirectionURL = '/',
		?array $parameters = [],
		?FormInterface $form = null,
		?TrickInterface $trick = null
	): Response;
}
