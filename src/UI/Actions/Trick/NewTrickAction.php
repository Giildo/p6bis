<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\UI\Forms\Trick\NewTrickType;
use App\UI\Responders\Interfaces\Trick\NewTrickResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig_Error_Loader;
use Twig_Error_Runtime;
use Twig_Error_Syntax;

class NewTrickAction
{
    /**
     * @var NewTrickResponderInterface
     */
    private $responder;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var NewTrickHandlerInterface
     */
    private $handler;

    /**
     * NewTrickAction constructor.
     * @param NewTrickResponderInterface $responder
     * @param FormFactoryInterface $formFactory
     * @param NewTrickHandlerInterface $handler
     */
    public function __construct(
        NewTrickResponderInterface $responder,
        FormFactoryInterface $formFactory,
        NewTrickHandlerInterface $handler
    ) {
        $this->responder = $responder;
        $this->formFactory = $formFactory;
        $this->handler = $handler;
    }

    /**
     * @Route(path="/espace-utilisateur/trick/nouvelle-figure", name="New_trick")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function newTrick(Request $request): Response
    {
        $form = $this->formFactory->create(NewTrickType::class)
                                  ->handleRequest($request);

        if (!is_null($this->handler->handle($form))) {
            return $this->responder->response(true);
        }

        return $this->responder->response(false, $form);
    }
}
