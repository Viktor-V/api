<?php

declare(strict_types=1);

namespace App\Admin\Domain\DataTransfer;

use App\Common\Domain\DataTransfer\ResourceInterface;

final class Admin implements ResourceInterface
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password
    ) {
    }
}
