<?php

namespace App\UI\Presenters\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use Twig\Environment;

class ShowTrickPresenter implements ShowTrickPresenterInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * ShowTrickPresenter constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Trick $trick
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function showTrickPresentation(Trick $trick): string
    {
        return $this->twig->render('Trick/show.html.twig', compact('trick'));
    }
}
