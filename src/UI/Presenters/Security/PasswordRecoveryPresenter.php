<?php

namespace App\UI\Presenters\Security;

use App\UI\Presenters\Interfaces\Security\PasswordRecoveryPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class PasswordRecoveryPresenter implements PasswordRecoveryPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * PasswordRecoveryPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function passwordRecoveryPresentation(
        ?FormInterface $form = null,
        ?string $typeName = ''
    ): string {
        if (!is_null($form)) {
            switch ($typeName) {
                case 'forPassword':
                    return $this->twig->render('Security/passwordRecovery/forPassword.html.twig', [
                        'form' => $form->createView(),
                    ]);
                    break;

                case 'forUsername':
                    return $this->twig->render('Security/passwordRecovery/forUsername.html.twig', [
                        'form' => $form->createView(),
                    ]);
                    break;
            }
        }

        return $this->twig->render('Security/passwordRecovery/mailSuccess.html.twig');
    }
}
