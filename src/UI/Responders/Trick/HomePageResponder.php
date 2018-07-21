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
     * @param array|null $tricks
     * @return Response
     */
    public function homePageResponse(?array $tricks = []): Response
    {
        return new Response(
            $this->presenter->homePagePresentation($tricks)
        );
    }
}
