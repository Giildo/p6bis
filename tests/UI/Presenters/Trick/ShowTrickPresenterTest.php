<?php

namespace App\Tests\UI\Presenters\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Presenters\Trick\ShowTrickPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ShowTrickPresenterTest extends KernelTestCase
{
    public function testIfTheReturnOfPresentationIsAString()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');

        $presenter = new ShowTrickPresenter($twig);

        self::assertInstanceOf(ShowTrickPresenterInterface::class, $presenter);

        $trick = $this->createMock(Trick::class);

        $formView = $this->createMock(FormView::class);
        $form = $this->createMock(FormInterface::class);
        $form->method('createView')->willReturn($formView);

        self::assertInternalType('string', $presenter->showTrickPresentation(
            $trick,
            $form
        ));
    }
}
