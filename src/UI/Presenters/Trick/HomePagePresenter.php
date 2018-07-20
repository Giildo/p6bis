<?php

namespace App\UI\Presenters\Trick;

use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use Twig\Environment;

class HomePagePresenter implements HomePagePresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * HomePagePresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param array|null $tricks
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function homePagePresentation(?array $tricks = []): string
    {
        return $this->twig->render('Trick/home.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
