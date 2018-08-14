<?php

namespace App\UI\Actions\Trick;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TrickDeletionAction
{
    /**
     * @Route(
     *     path="/espace-utilisateur/trick/suppression/{trickSlug}",
     *     requirements={"trickSlug"="\w+"},
     *     name="Trick_deletion"
     * )
     *
     * @param Request $request
     */
    public function delete(Request $request)
    {

    }
}
