<?php

declare(strict_types=1);

namespace App\Security\Domain\DataTransfer;

final class Me
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $status,
        public readonly string $createdAt,
        public readonly string $updatedAt
    ) {
    }
}
