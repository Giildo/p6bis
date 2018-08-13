<?php

namespace App\UI\Presenters\Trick;

use App\UI\Presenters\Interfaces\Trick\NewTrickPresenterInterface;
use Symfony\Component\Form\FormInterface;
use Twig\Environment;

class NewTrickPresenter implements NewTrickPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * NewTrickPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function newTrickPresentation(FormInterface $form): string
    {
        return $this->twig->render('Trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
