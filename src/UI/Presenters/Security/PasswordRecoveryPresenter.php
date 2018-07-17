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
     * @param null|FormInterface $form
     * @param null|string $typeName
     * @param bool|null $mailerSuccess
     * @return string | null
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordRecoveryPresentation(
        ?FormInterface $form = null,
        ?string $typeName = '',
        ?bool $mailerSuccess = false
    ): ?string {
        if (!is_null($form)) {
            if ($typeName === 'forPassword') {
                return $this->twig->render('Security/passwordRecovery/forPassword.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            if ($typeName === 'forUsername') {
                return $this->twig->render('Security/passwordRecovery/forUsername.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
        }

        if ($mailerSuccess) {
            return $this->twig->render('Security/passwordRecovery/mailSuccess.html.twig');
        }

        return null;
    }
}
