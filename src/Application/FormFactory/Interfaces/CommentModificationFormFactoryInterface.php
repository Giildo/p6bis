<?php

namespace App\Application\FormFactory\Interfaces;

use App\UI\Forms\Comment\AddCommentType;
use App\UI\Forms\Comment\CommentModificationType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface CommentModificationFormFactoryInterface
 *
 * @package App\Application\FormFactory\Interfaces
 */
interface CommentModificationFormFactoryInterface
{
    /**
     * Create a custom form:
     * - @uses AddCommentType if the path hasn't query
     * - @uses CommentModificationType if the path has queries :
     *      - id The ID of comment
     *      - action The action for the comment
     *
     * @param Request $request The request returns the session. The session
     * returns the comment if he exists.
     *
     * @return FormInterface
     */
    public function create(Request $request): FormInterface;
}