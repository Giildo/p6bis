<?php

namespace App\UI\Presenters\Trick;

use App\UI\Presenters\Interfaces\Trick\HomeAJAXMethodPresenterInterface;
use Twig\Environment;

class HomeAJAXMethodPresenter implements HomeAJAXMethodPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * HomeAJAXMethodPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function presentation(array $tricks)
    {
        return $this->twig->render(
            'Trick/home_load.html.twig', [
            'tricks' => $tricks
        ]);
    }
}
