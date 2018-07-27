<?php

namespace App\Tests\UI\Presenters\Trick;

use App\Domain\Model\Trick;
use App\UI\Presenters\Interfaces\Trick\ShowTrickPresenterInterface;
use App\UI\Presenters\Trick\ShowTrickPresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ShowTrickPresenterTest extends KernelTestCase
{
    private $presenter;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');

        $this->presenter = new ShowTrickPresenter($twig);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(ShowTrickPresenterInterface::class, $this->presenter);
    }

    public function testIfTheReturnOfPresentationIsAString()
    {
        $trick = $this->createMock(Trick::class);
        $pictures = [];
        $videos = [];

        self::assertInternalType('string', $this->presenter->showTrickPresentation(
            $trick,
            $pictures,
            $videos
        ));
    }
}
