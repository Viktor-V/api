<?php

declare(strict_types=1);

namespace App\Admin\Domain\ValueObject;

use App\Common\Domain\Assert\Assertion;

final class ConfirmationToken
{
    private string $token;

    public function __construct(
        string $token
    ) {
        Assertion::notEmpty($token);

        $this->token = $token;
    }

    public function __toString(): string
    {
        return $this->token;
    }
}
