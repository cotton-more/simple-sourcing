<?php

namespace CottonMore\SimpleSourcing;

use Generator;

interface AggregateRoot
{
    public function aggregateRootId(): string;

    public function aggregateRootVersion(): int;

    public function reconstituteFromEvents(Generator $events);

    public function releaseEvents(): array;
}
