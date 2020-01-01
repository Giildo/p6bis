<?php

namespace App\UI\Presenters\Trick;

use App\Domain\Model\Interfaces\TrickInterface;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use Symfony\Component\Form\FormInterface;
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
    public function showTrickPresentation(
        TrickInterface $trick,
        FormInterface $formComment,
        int $pageNumber,
        ?array $comments = [],
        ?int $currentPage = 0
    ): string {
        return $this->twig->render(
            'Trick/show.html.twig', [
            'trick'       => $trick,
            'formComment' => $formComment->createView(),
            'comments'    => $comments,
            'pageNumber'  => $pageNumber,
            'currentPage' => $currentPage,
        ]);
    }
}
