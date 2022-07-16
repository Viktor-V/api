<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Platform\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use ArrayObject;

/**
 * @psalm-suppress all
 */
final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    public function __construct(
        private AbstractNormalizer $decorated
    ) {
    }

    public function normalize(
        mixed $object,
        string $format = null,
        array $context = []
    ): ArrayObject|array|string|int|float|bool|null {
        return $this->decorated->normalize($object, $format, $context);
    }

    public function denormalize(
        mixed $data,
        string $type,
        string $format = null,
        array $context = []
    ) {
        $data = $this->decorated->denormalize($data, $type, $format, $context);

        if (method_exists($data, 'getUuid') && method_exists($data, 'setUuid') && $data->getUuid() === '') {
            $data->setUuid(Uuid::v4()->__toString());
        }

        return $data;
    }

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $this->decorated->supportsNormalization($data, $format);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    public function setSerializer(SerializerInterface $serializer): void
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }
}
