<?php

namespace App\Tests\UI\Presenters\Trick;

use App\UI\Forms\Trick\NewTrickType;
use App\UI\Presenters\Interfaces\Trick\NewTrickPresenterInterface;
use App\UI\Presenters\Trick\NewTrickPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class NewTrickPresenterTest extends KernelTestCase
{
    private $presenter;

    private $formFactory;

    protected function setUp()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');
        $this->formFactory = $kernel->getContainer()->get('form.factory');

        $this->presenter = new NewTrickPresenter($twig);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(NewTrickPresenterInterface::class, $this->presenter);
    }

    public function testIfReturnOfPresentationIsString()
    {
        $form = $this->formFactory->create(NewTrickType::class);

        $response = $this->presenter->newTrickPresentation($form);

        self::assertInternalType('string', $response);
    }
}
