<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\Parameter;

class PatchConfirmationFactory implements OpenApiFactoryInterface
{
    private const PATH = '/api/admin/{uuid}/confirm/{confirmationToken}';

    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /** @var PathItem $pathItem */
        $pathItem = $openApi->getPaths()->getPath(self::PATH);
        /** @var Operation $operation */
        $operation = $pathItem->getPatch();

        $parameters = array_merge(
            $operation->getParameters(),
            [new Parameter('confirmationToken', 'path', 'Confirmation token', true)]
        );

        $openApi->getPaths()->addPath(self::PATH, $pathItem->withPatch($operation->withParameters($parameters)));

        return $openApi;
    }
}
