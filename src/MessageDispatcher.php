<?php

namespace CottonMore\SimpleSourcing;

interface MessageDispatcher
{
    public function dispatch(Message ...$messages);
}
