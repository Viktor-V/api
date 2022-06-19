<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Common\Domain\Assert\Assertion;

final class Uuid
{
    protected string $uuid;

    public function __construct(
        string $uuid
    ) {
        Assertion::notEmpty($uuid);
        Assertion::uuid($uuid);

        $this->uuid = $uuid;
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
