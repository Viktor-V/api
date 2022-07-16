<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Platform;

use RuntimeException;

trait OperationTrait
{
    public function operationName(array $context): string
    {
        if (isset($context['collection_operation_name'])) {
            return (string) $context['collection_operation_name'];
        }

        if (isset($context['item_operation_name'])) {
            return (string) $context['item_operation_name'];
        }

        throw new RuntimeException('Operation name could not be null');
    }
}
