<?php

namespace CottonMore\SimpleSourcing;

interface Header
{
    public const AGGREGATE_ROOT_ID = '__aggregate_root_id';
    public const AGGREGATE_ROOT_VERSION = '__aggregate_root_version';
    public const TIME_OF_RECORDING = '__time_of_recording';
    public const EVENT_NAME = '__event_name';
}
