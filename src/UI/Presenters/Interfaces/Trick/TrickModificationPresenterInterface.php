<?php

namespace App\UI\Presenters\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

interface TrickModificationPresenterInterface {
    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return string
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
	public function trickModificationPresentation(
		FormInterface $form,
		TrickInterface $trick
	): string;
}