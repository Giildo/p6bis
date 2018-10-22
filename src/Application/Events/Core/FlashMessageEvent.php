<?php

namespace App\Application\Events\Core;

use App\Application\Events\Interfaces\Core\FlashMessageEventInterface;
use Symfony\Component\EventDispatcher\Event;

class FlashMessageEvent extends Event implements FlashMessageEventInterface
{
    /**
     * @var bool Defines if the message event is a
     * - 'success' => true
     * - 'error'=> false
     */
    private $type;

    /**
     * @var string The content of message event
     */
    private $message;

    /**
     * FlashMessageEvent constructor.
     *
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
     * {@inheritdoc}
     */
    public function getType(): bool
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
