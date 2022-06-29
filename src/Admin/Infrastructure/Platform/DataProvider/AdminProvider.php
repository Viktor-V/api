<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\Pagination;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Admin\Application\DataTransfer\Admin;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Application\UseCase\Query\List\ListQuery;
use App\Common\Application\Query\QueryBusInterface;
use App\Common\Infrastructure\Platform\DataProvider\Paginator;
use Generator;

class AdminProvider implements
    ContextAwareCollectionDataProviderInterface,
    ItemDataProviderInterface,
    RestrictedDataProviderInterface
{
    public function __construct(
        private QueryBusInterface $bus,
        private Pagination $pagination
    ) {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $page = $this->pagination->getPage($context);
        $limit = $this->pagination->getLimit($resourceClass, $operationName, $context);

        /** @var Generator $adminGenerator */
        $adminGenerator = $this->bus->handle(new ListQuery($page, $limit));

        return new Paginator(
            $adminGenerator,
            $page,
            $limit
        );
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        /** @var string $id */
        $query = new FindQuery($id);

        /** @var Admin $admin */
        $admin = $this->bus->handle($query);

        return $admin;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Admin::class === $resourceClass;
    }
}
