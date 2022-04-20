<?php

namespace CottonMore\SimpleSourcing;

final class MessageDispatcherChain implements MessageDispatcher
{
    private array $dispatchers;

    public function __construct(MessageDispatcher ...$dispatchers)
    {
        $this->dispatchers = $dispatchers;
    }

    public function dispatch(Message ...$messages): void
    {
        foreach ($this->dispatchers as $dispatcher) {
            $dispatcher->dispatch(...$messages);
        }
    }
}
