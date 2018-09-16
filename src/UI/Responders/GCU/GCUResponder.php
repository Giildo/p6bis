<?php

namespace App\UI\Responders\GCU;

use App\UI\Presenters\Interfaces\GCU\GCUPresenterInterface;
use App\UI\Responders\Interfaces\GCU\GCUResponderInterface;
use Symfony\Component\HttpFoundation\Response;

class GCUResponder implements GCUResponderInterface
{
    /**
     * @var GCUPresenterInterface
     */
    private $presenter;

    /**
     * GCUResponder constructor.
     * @param GCUPresenterInterface $presenter
     */
    public function __construct(GCUPresenterInterface $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * {@inheritdoc}
     */
    public function response(): Response
    {
        return new Response($this->presenter->presentation());
    }
}
