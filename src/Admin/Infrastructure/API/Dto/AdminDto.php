<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\API\Dto;

final class AdminDto
{
    public function __construct(
        public readonly int $id
    ) {
    }
}
