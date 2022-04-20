<?php

namespace CottonMore\SimpleSourcing;

final class SynchronousMessageDispatcher implements MessageDispatcher
{
    /** @var array<int, MessageConsumer> */
    private array $consumers;

    public function __construct(MessageConsumer ...$consumers)
    {
        $this->consumers = $consumers;
    }

    public function dispatch(Message ...$messages): void
    {
        foreach ($messages as $message) {
            foreach ($this->consumers as $consumer) {
                $consumer->handle($message);
            }
        }
    }
}
