<?php

declare(strict_types=1);

namespace App\Admin\Domain\DataTransfer;

final class AdminWrite
{
    public function __construct(
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password
    ) {
    }
}
