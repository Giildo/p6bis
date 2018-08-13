<?php

namespace App\Tests\Application\Helpers;

use App\Application\Helpers\Interfaces\PictureAndVideoTokenManagerInterface;
use App\Application\Helpers\PictureAndVideoTokenManager;
use App\Domain\Model\Interfaces\TrickInterface;
use App\Domain\Model\Picture;
use App\Domain\Model\Video;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PictureAndVideoTokenManagerTest extends TestCase
{
    private $session;

    private $helper;


    public function setUp()
    {
        $this->session = new Session(new MockArraySessionStorage());

        $tokenGenerator = $this->createMock(TokenGeneratorInterface::class);
        $tokenGenerator->method('generateToken')
                       ->willReturn('bRlkYWJpFLpL_pgrh-zpclfYuKXwRN1XIjMJ4D_g_8M');
        $this->helper = new PictureAndVideoTokenManager($tokenGenerator);
    }

    public function testConstructor()
    {
        self::assertInstanceOf(PictureAndVideoTokenManagerInterface::class, $this->helper);
    }

    public function testTheCreationOfTokensInEntities()
    {
        $trick = $this->createMock(TrickInterface::class);

        $videos[] = new Video(
            'B3Lhid5_HJc',
            $trick
        );

        $videos[] = new Video(
            'Jdj45-_eDcS',
            $trick
        );

        $pictures[] = new Picture(
            'mute20180814150202_1',
            'Description de la photo',
            'jpeg',
            false,
            $trick
        );

        $this->helper->createTokens($pictures, $videos);

        self::assertEquals('bRlkYWJpFLpL_pgrh-zpclfYuKXwRN1XIjMJ4D_g_8M', $pictures[0]->getDeleteToken());
        self::assertEquals('bRlkYWJpFLpL_pgrh-zpclfYuKXwRN1XIjMJ4D_g_8M', $videos[0]->getDeleteToken());
        self::assertEquals('bRlkYWJpFLpL_pgrh-zpclfYuKXwRN1XIjMJ4D_g_8M', $videos[1]->getDeleteToken());
    }
}
