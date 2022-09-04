<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\AdminSecurity\Application\UseCase\Query\Me\MeQuery;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Infrastructure\Security\AdminIdentity;
use App\Common\Application\Query\QueryBusInterface;
use Symfony\Component\Security\Core\Security;

class MeProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security,
        private QueryBusInterface $bus
    ) {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?object
    {
        /** @var AdminIdentity $adminIdentity */
        $adminIdentity = $this->security->getUser();

        /** @var Admin */
        return $this->bus->handle(new MeQuery($adminIdentity->getUserIdentifier()));
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Admin::class && $operationName === 'get_me';
    }
}
