<?php

namespace CottonMore\SimpleSourcing;

interface AggregateRootRepository
{
    public function retrieve(AggregateRoot $aggregateRoot): void;

    public function persist(AggregateRoot $aggregateRoot): void;
}