<?php

namespace App\UI\Responders\Interfaces\User;

use Symfony\Component\HttpFoundation\RedirectResponse;

interface ProfilePictureDeletePictureResponderInterface
{
    public function response(): RedirectResponse;
}
