<?php

namespace App\Tests\UI\Presenters\Trick;

use App\UI\Presenters\Interfaces\Trick\HomePagePresenterInterface;
use App\UI\Presenters\Trick\HomePagePresenter;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class HomePagePresenterTest extends KernelTestCase
{
    private $presenter;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $twig = $kernel->getContainer()->get('twig');

        $this->presenter = new HomePagePresenter($twig);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(HomePagePresenterInterface::class, $this->presenter);
    }

    public function testIfTheReturnOfPresentationIsAString()
    {
        self::assertInternalType('string', $this->presenter->homePagePresentation());
    }
}
