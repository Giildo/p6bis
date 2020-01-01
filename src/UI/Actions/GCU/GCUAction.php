<?php

namespace App\UI\Actions\GCU;

use App\UI\Responders\Interfaces\GCU\GCUResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class GCUAction
{
    /**
     * @var GCUResponderInterface
     */
    private $responder;

    /**
     * GCUAction constructor.
     * @param GCUResponderInterface $responder
     */
    public function __construct(GCUResponderInterface $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @Route(path="/conditions_generales_dutilisation", name="GCU")
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function gcuPresentation()
    {
        return $this->responder->response();
    }
}
