<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\API\Dto;

use App\Common\Infrastructure\API\UuidTrait;

final class AdminDto
{
    use UuidTrait;

    public function __construct(
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password
    ) {
    }
}
