<?php

namespace App\UI\Presenters\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
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
     * {@inheritdoc}
     */
    public function showTrickPresentation(TrickInterface $trick): string
    {
        return $this->twig->render('Trick/show.html.twig', compact('trick'));
    }
}
