<?php

namespace App\UI\Actions\Trick;

use App\Application\Handlers\Interfaces\Forms\Trick\NewTrickHandlerInterface;
use App\UI\Forms\Trick\NewTrickType;
use App\UI\Responders\Interfaces\Trick\NewTrickResponderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route(path="/espace-utilisateur/trick/nouvelle-figure", name="new_trick")
     *
     * @param Request $request
     * @return Response
     */
    public function newTrick(Request $request): Response
    {
        $form = $this->formFactory->create(NewTrickType::class)
                                  ->handleRequest($request);

        if (!is_null($trick = $this->handler->handle($form))) {
            return $this->responder->response(true, $trick);
        }

        return $this->responder->response(false, null, $form);
    }
}
