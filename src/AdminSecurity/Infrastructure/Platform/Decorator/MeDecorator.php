<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\Decorator;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\Operation;

class MeDecorator implements OpenApiFactoryInterface
{
    private const PATH = '/api/admin/auth/me';

    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        /** @var PathItem $pathItem */
        $pathItem = $openApi->getPaths()->getPath(self::PATH);
        /** @var Operation $operation */
        $operation = $pathItem->getGet();

        /* Remove uuid from parameters */
        $openApi->getPaths()->addPath(self::PATH, $pathItem->withGet($operation->withParameters([])));

        return $openApi;
    }
}
