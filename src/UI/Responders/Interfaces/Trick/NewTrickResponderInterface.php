<?php

namespace App\UI\Responders\Interfaces\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

interface NewTrickResponderInterface
{

    /**
     * @param bool $redirection
     * @param TrickInterface|null $trick
     * @param null|FormInterface $form
     * @return Response
     */
    public function response(
        bool $redirection,
        ?TrickInterface $trick = null,
        ?FormInterface $form = null
    ): Response;
}
