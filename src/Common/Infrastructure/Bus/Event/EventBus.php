<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Event;

use App\Common\Domain\Event\EventBusInterface;
use App\Common\Domain\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus
    ) {
    }

    public function dispatch(EventInterface $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
