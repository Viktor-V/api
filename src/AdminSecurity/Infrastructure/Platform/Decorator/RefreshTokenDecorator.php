<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\Decorator;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\Model\Operation;
use RuntimeException;
use ArrayObject;

class RefreshTokenDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);

        $schemas = $openApi->getComponents()->getSchemas();
        if (!$schemas) {
            throw new RuntimeException('Schemas not defined.');
        }

        $schemas['Token'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
            ],
        ]);

        $schemas['RefreshToken'] = new ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'example' => 'refreshToken',
                ]
            ],
        ]);

        $pathItem = new PathItem(
            ref: 'JWT Token Refresh',
            post: new Operation(
                operationId: 'postRefreshToken',
                tags: ['Admin Security'],
                responses: [
                    '200' => [
                        'description' => 'Refresh JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh JWT token.',
                description: 'Refresh JWT token.',
                requestBody: new RequestBody(
                    content: new ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/RefreshToken',
                            ],
                        ],
                    ]),
                ),
            ),
        );
        $openApi->getPaths()->addPath('/api/admin/auth/refresh', $pathItem);

        return $openApi;
    }
}
