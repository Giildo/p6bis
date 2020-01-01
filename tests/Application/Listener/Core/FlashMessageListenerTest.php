<?php

namespace App\Tests\Application\Listener\Core;

use App\Application\Events\Core\FlashMessageEvent;
use App\Application\Listener\Core\FlashMessageListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class FlashMessageListenerTest extends TestCase
{
    public function testMessageIsSuccessMessage()
    {
        $flashBag = new FlashBag();
        $listener = new FlashMessageListener($flashBag);

        $flashEvent = new FlashMessageEvent(
            true,
            'L\'enregistrement est un succès.'
        );

        $listener->addFlashMessage($flashEvent);

        $messageFlash = $flashBag->all();

        self::assertArrayHasKey('success', $messageFlash);
        self::assertEquals(
            'L\'enregistrement est un succès.',
            $messageFlash['success'][0]
        );
    }

    public function testMessageIsErrorMessage()
    {
        $flashBag = new FlashBag();
        $listener = new FlashMessageListener($flashBag);

        $flashEvent = new FlashMessageEvent(
            false,
            'Une erreur est survenue.'
        );

        $listener->addFlashMessage($flashEvent);

        $messageFlash = $flashBag->all();

        self::assertArrayHasKey('error', $messageFlash);
        self::assertEquals(
            'Une erreur est survenue.',
            $messageFlash['error'][0]
        );
    }
}
