<?php

namespace App\Application\Listener\Core;

use App\Application\Events\Interfaces\Core\FlashMessageEventInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashMessageListener
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * FlashMessageListener constructor.
     * @param FlashBagInterface $flashBag
     */
    public function __construct(
        FlashBagInterface $flashBag
    ) {
        $this->flashBag = $flashBag;
    }

    public function addFlashMessage(FlashMessageEventInterface $event)
    {
        $type = 'error';

        if ($event->getType()) {
            $type = 'success';
        }

        $this->flashBag->add(
            $type,
            $event->getMessage()
        );
    }
}
