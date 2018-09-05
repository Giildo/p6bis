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
     * {@inheritdoc}
     */
    public function homePagePresentation(
        array $tricks,
        int $pageNumber,
        int $currentPage
    ): string {
        return $this->twig->render('Trick/home.html.twig', [
            'tricks'      => $tricks,
            'pageNumber'  => $pageNumber,
            'currentPage' => $currentPage,
        ]);
    }
}
