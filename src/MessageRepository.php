<?php

namespace CottonMore\SimpleSourcing;

use Generator;

interface MessageRepository
{
    public function retrieveAll(AggregateRoot $aggregateRoot): Generator;

    public function persist(Message ...$messages): void;
}
