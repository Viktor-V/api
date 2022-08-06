<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\AdminSecurity\Application\UseCase\Query\FindByConfirmationToken\FindByConfirmationTokenQuery;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\Common\Application\Query\QueryBusInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private QueryBusInterface $bus,
        private RequestStack $requestStack
    ) {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        $data = $this->requestStack->getCurrentRequest()?->toArray();

        if (empty($data)) {
            return null;
        }

        /** @var Admin */
        return $this->bus->handle(new FindByConfirmationTokenQuery((string) ($data['confirmationToken'] ?? null)));
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Admin::class && $operationName === 'patch_confirm';
    }
}
