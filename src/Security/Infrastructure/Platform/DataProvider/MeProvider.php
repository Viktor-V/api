<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Platform\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Security\Domain\DataTransfer\Me;
use Symfony\Component\Security\Core\Security;

class MeProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return new Me('test', 'test', 'test', 'test', 'test', 'test', 'test');
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return true;
    }
}
