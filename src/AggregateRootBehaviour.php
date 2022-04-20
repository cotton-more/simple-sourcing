<?php

namespace CottonMore\SimpleSourcing;

use Generator;

trait AggregateRootBehaviour
{
    private int $aggregateRootVersion = 0;

    private array $recordedEvents = [];

    protected function apply(object $event): void
    {
        $parts = explode('\\', get_class($event));
        $methodName = 'apply' . end($parts);

        if (method_exists($this, $methodName)) {
            $this->{$methodName}($event);
        }
    }

    protected function recordThat(object $event): void
    {
        ++$this->aggregateRootVersion;

        $this->apply($event);

        $this->recordedEvents[] = $event;
    }

    public function aggregateRootVersion(): int
    {
        return $this->aggregateRootVersion;
    }

    public function reconstituteFromEvents(Generator $events): void
    {
        /** @var object $event */
        foreach ($events as $event) {
            $this->apply($event);
        }

        $this->aggregateRootVersion = $events->getReturn() ?: 0;
    }

    public function releaseEvents(): array
    {
        $events = $this->recordedEvents;

        $this->recordedEvents = [];

        return $events;
    }
}
