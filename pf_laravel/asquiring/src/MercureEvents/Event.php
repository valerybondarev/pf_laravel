<?php

namespace App\MercureEvents;

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class Event
{
    public function __construct(private readonly HubInterface $hub) {}

    /**
     * Метод для публикации ивента в сокет сервер.
     *
     * @param Type $type
     * @param string $objectId
     * @param array $eventBody
     *
     * @return void
     */
    public function publish(Type $type, string $objectId, array $eventBody = []): void
    {
        $this->hub->publish(
            new Update(
                $this->resolveTopicByType($type, $objectId),
                json_encode($eventBody)
            )
        );
    }

    /**
     * Метод для определения топика по типу ивента.
     *
     * @param Type $type
     * @param string $objectId
     *
     * @return string
     */
    private function resolveTopicByType(Type $type, string $objectId): string
    {
        return match ($type) {
            Type::ORDER_STATUS_HAS_BEEN_CHANGED => "payments/$objectId/status",
        };
    }
}