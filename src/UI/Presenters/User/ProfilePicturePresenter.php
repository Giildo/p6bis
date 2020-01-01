<?php

namespace App\UI\Presenters\User;

use App\Domain\Model\Interfaces\UserInterface;
use App\UI\Presenters\Interfaces\User\ProfilePicturePresenterInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class ProfilePicturePresenter implements ProfilePicturePresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ProfilePicturePresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function presentation(
        FormInterface $form,
        UserInterface $user
    ): string {
        return $this->twig->render(
            'User/pictureProfile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
