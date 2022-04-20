<?php

namespace CottonMore\SimpleSourcing;

interface MessageConsumer
{
    public function handle(Message $message): void;
}
