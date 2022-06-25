<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\API;

trait UuidTrait
{
    private string $uuid;

    public function injectUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
