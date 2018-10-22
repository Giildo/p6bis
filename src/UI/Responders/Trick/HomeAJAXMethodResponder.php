<?php

namespace App\UI\Responders\Trick;

use App\UI\Presenters\Interfaces\Trick\HomeAJAXMethodPresenterInterface;
use App\UI\Responders\Interfaces\Trick\HomeAJAXMethodResponderInterface;
use Symfony\Component\HttpFoundation\Response;

class HomeAJAXMethodResponder implements HomeAJAXMethodResponderInterface
{
    /**
     * @var HomeAJAXMethodPresenterInterface
     */
    private $presenter;

    /**
     * HomeAJAXMethodResponder constructor.
     * @param HomeAJAXMethodPresenterInterface $presenter
     */
    public function __construct(HomeAJAXMethodPresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function response(?array $tricks = [])
    {
        return (!empty($tricks)) ?
            new Response($this->presenter->presentation($tricks)) :
            new Response('', Response::HTTP_NOT_FOUND);
    }
}
