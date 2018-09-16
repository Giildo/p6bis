<?php

namespace App\UI\Presenters\GCU;

use App\UI\Presenters\Interfaces\GCU\GCUPresenterInterface;
use Twig\Environment;

class GCUPresenter implements GCUPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * GCUPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function presentation(): string
    {
        return $this->twig->render('gcu/gcu.html.twig');
    }
}
