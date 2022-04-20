<?php

namespace CottonMore\SimpleSourcing;

use Generator;

final class EventSourcedAggregateRootRepository implements AggregateRootRepository
{
    private MessageRepository $messages;
    private MessageDecorator $decorator;
    private MessageDispatcher $dispatcher;

    public function __construct(
        MessageRepository $messages,
        MessageDecorator  $decorator = null,
        MessageDispatcher $dispatcher = null
    ) {
        $this->messages = $messages;
        $this->decorator = $decorator ?? new MessageDecoratorChain;
        $this->dispatcher = $dispatcher ?? new SynchronousMessageDispatcher;
    }

    private function retrieveAllEvents(AggregateRoot $aggregateRoot): Generator
    {
        $messages = $this->messages->retrieveAll($aggregateRoot);

        foreach ($messages as $message) {
            yield $message->event();
        }

        return $messages->getReturn();
    }

    public function retrieve(AggregateRoot $aggregateRoot): void
    {
        $events = $this->retrieveAllEvents($aggregateRoot);

        $aggregateRoot->reconstituteFromEvents($events);
    }

    public function persist(AggregateRoot $aggregateRoot): void
    {
        $events = $aggregateRoot->releaseEvents();
        if (0 === count($events)) {
            return;
        }

        $aggregateRootVersion = $aggregateRoot->aggregateRootVersion();
        $aggregateRootVersion -= count($events);

        $metadata = [
            Header::AGGREGATE_ROOT_ID => $aggregateRoot->aggregateRootId(),
        ];
        $messages = array_map(
            function (object $event) use ($metadata, &$aggregateRootVersion) {
                $message = (new Message(
                    $event,
                    $metadata + [Header::EVENT_NAME => get_class($event)]
                ))->withAggregateVersion(++$aggregateRootVersion);

                return $this->decorator->decorate($message);
            },
            $events
        );

        $this->messages->persist(...$messages);
        $this->dispatcher->dispatch(...$messages);
    }
}
