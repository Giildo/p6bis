<?php

namespace App\Application\Events\Core;

use App\Application\Events\Interfaces\Core\FlashMessageEventInterface;
use Symfony\Component\EventDispatcher\Event;

class FlashMessageEvent extends Event implements FlashMessageEventInterface
{
    /**
     * @var bool
     */
    private $type;

    /**
     * @var string
     */
    private $message;

    /**
     * FlashMessageEvent constructor.
     * @param bool $type
     * @param string $message
     */
    public function __construct(
        bool $type,
        string $message
    ) {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function getType(): bool
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
