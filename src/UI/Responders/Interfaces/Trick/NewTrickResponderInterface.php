<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Trick;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface NewTrickResponderInterface
{

    /**
     * @param bool $redirection
     * @param null|Trick $trick
     * @param null|FormInterface $form
     * @return Response
     */
    public function response(
        bool $redirection,
        ?Trick $trick = null,
        ?FormInterface $form = null
    ): Response;
}
