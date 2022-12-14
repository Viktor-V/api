<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\EventSubscriber;

use App\Common\Domain\Entity\Aggregate;
use App\Common\Domain\Event\EventBusInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;

final class DomainEventSubscriber implements EventSubscriber
{
    /**
     * @var Aggregate[]
     */
    private array $entities = [];

    public function __construct(
        private EventBusInterface $eventBus
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events::postFlush,
        ];
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        foreach ($this->entities as $entity) {
            foreach ($entity->popEvents() as $event) {
                $this->eventBus->dispatch($event);
            }
        }
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    private function keepAggregateRoots(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!($entity instanceof Aggregate)) {
            return;
        }

        $this->entities[] = $entity;
    }
}
