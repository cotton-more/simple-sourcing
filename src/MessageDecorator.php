<?php

namespace CottonMore\SimpleSourcing;

interface MessageDecorator
{
    public function decorate(Message $message): Message;
}
