<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Platform\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Common\Domain\DataTransfer\ResourceInterface;
use Symfony\Component\Uid\Uuid;
use ReflectionClass;

class InputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        $arguments = array_merge(['uuid' => Uuid::v4()->__toString()], get_object_vars($object));

        return (new ReflectionClass($to))->newInstanceArgs($arguments);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return !$data instanceof ResourceInterface;
    }
}
