<?php

namespace App\Application\Events\Interfaces\Core;

/**
 * Event for response
 *
 * Interface FlashMessageEventInterface
 *
 * @package App\Application\Events\Interfaces\Core
 */
interface FlashMessageEventInterface
{
    const FLASH_MESSAGE = 'app.event.flash_message';

    /**
     * @return bool
     */
    public function getType(): bool;

    /**
     * @return string
     */
    public function getMessage(): string;
}