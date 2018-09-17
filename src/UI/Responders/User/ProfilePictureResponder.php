<?php

namespace App\UI\Responders\User;

use App\Domain\Model\Interfaces\UserInterface;
use App\UI\Presenters\Interfaces\User\ProfilePicturePresenterInterface;
use App\UI\Responders\Interfaces\User\ProfilePictureResponderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

class ProfilePictureResponder implements ProfilePictureResponderInterface
{
    /**
     * @var ProfilePicturePresenterInterface
     */
    private $presenter;

    /**
     * ProfilePictureResponder constructor.
     * @param ProfilePicturePresenterInterface $presenter
     */
    public function __construct(
        ProfilePicturePresenterInterface $presenter
    ) {
        $this->presenter = $presenter;
    }

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return Response
     */
    public function response(FormInterface $form, UserInterface $user): Response
    {
        return new Response($this->presenter->presentation($form, $user));
    }
}
