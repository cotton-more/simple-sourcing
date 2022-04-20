<?php

namespace CottonMore\SimpleSourcing;

use DateTimeImmutable;

final class Message
{
    public const TIME_OF_RECORDING_FORMAT = 'Y-m-d H:i:s.uO';

    private object $event;
    private array $headers;

    public function __construct(object $event, array $headers = [])
    {
        $this->event = $event;
        $this->headers = $headers;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function event(): object
    {
        return $this->event;
    }

    /**
     * @param int|string|null $value
     */
    public function withHeader(string $key, $value): Message
    {
        $clone = clone $this;
        $clone->headers[$key] = $value;

        return $clone;
    }

    public function withTimeOfRecording(DateTimeImmutable $timeOfRecording): Message
    {
        return $this->withHeader(
            Header::TIME_OF_RECORDING,
            $timeOfRecording->format(self::TIME_OF_RECORDING_FORMAT)
        );
    }

    public function withAggregateVersion(int $version): Message
    {
        return $this->withHeader(Header::AGGREGATE_ROOT_VERSION, $version);
    }

    public function aggregateVersion(): int
    {
        $version = $this->headers[Header::AGGREGATE_ROOT_VERSION] ?? 0;

        return (int)$version;
    }

    public function aggregateRootId(): ?string
    {
        return $this->headers[Header::AGGREGATE_ROOT_ID] ?? null;
    }
}
