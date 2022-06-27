<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Admin\Application\DataTransfer\Admin;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Common\Application\Query\QueryBusInterface;

class AdminProvider implements
    ContextAwareCollectionDataProviderInterface,
    ItemDataProviderInterface,
    RestrictedDataProviderInterface
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        // TODO: Implement getCollection() method.
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->bus->handle(new FindQuery($id));
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Admin::class === $resourceClass;
    }
}
