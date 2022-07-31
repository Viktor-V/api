<?php

declare(strict_types=1);

namespace App\AdminSecurity\Domain\DataTransfer;

final class Admin
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password,
        public readonly string $status,
        public readonly string $role,
        public readonly string $createdAt,
        public readonly string $updatedAt
    ) {
    }
}
