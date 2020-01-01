<?php

namespace App\UI\Responders\User;

use App\UI\Responders\Interfaces\User\ProfilePictureDeletePictureResponderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProfilePictureDeletePictureResponder
    implements ProfilePictureDeletePictureResponderInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * ProfilePictureDeletePictureResponder constructor.
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return RedirectResponse
     */
    public function response(): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('Profile_picture')
        );
    }
}
