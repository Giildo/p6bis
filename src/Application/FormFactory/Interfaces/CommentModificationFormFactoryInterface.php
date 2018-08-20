<?php

namespace App\Application\FormFactory\Interfaces;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface CommentModificationFormFactoryInterface
{
    /**
     * @param Request $request
     *
     * @return FormInterface
     */
    public function create(Request $request): FormInterface;
}