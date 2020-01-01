<?php

namespace App\UI\Responders\Trick;

use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use App\UI\Responders\Interfaces\Trick\HomePageResponderInterface;
use Symfony\Component\HttpFoundation\Response;

class HomePageResponder implements HomePageResponderInterface
{
    /**
     * @var HomePagePresenterInterface
     */
    private $presenter;

    /**
     * HomePageResponder constructor.
     * @param HomePagePresenterInterface $presenter
     */
    public function __construct(HomePagePresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function homePageResponse(
        ?array $tricks = [],
        ?int $numberPage = 0,
        ?int $currentPage = 0
    ): Response {
        return new Response(
            $this->presenter->homePagePresentation(
                $tricks,
                $currentPage,
                $numberPage
            )
        );
    }
}
