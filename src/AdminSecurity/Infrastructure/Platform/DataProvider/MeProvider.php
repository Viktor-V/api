<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\AdminSecurity\Application\UseCase\Query\Find\FindQuery;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\Common\Application\Query\QueryBusInterface;

class MeProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private QueryBusInterface $bus
    ) {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        /** @var Admin */
        return $this->bus->handle(new FindQuery());
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Admin::class && $operationName === 'get_me';
    }
}
