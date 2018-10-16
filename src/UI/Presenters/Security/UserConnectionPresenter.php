<?php

namespace App\UI\Presenters\Security;

use App\UI\Presenters\Interfaces\Security\UserConnectionPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Twig\Environment;

class UserConnectionPresenter implements UserConnectionPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * UserConnectionPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function userConnectionPresentation(
        FormInterface $form,
        ?AuthenticationException $error = null
    ): string {
        return $this->twig->render('Security/connection.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }
}
