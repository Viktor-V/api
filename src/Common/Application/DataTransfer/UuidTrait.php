<?php

declare(strict_types=1);

namespace App\Common\Application\DataTransfer;

trait UuidTrait
{
    private string $uuid;

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
