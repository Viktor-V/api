<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity\Embedded;

use App\Common\Domain\Assert\Assertion;

final class ConfirmationToken
{
    private string $confirmationToken;

    public function __construct(
        string $confirmationToken
    ) {
        Assertion::notEmpty($confirmationToken);

        $this->confirmationToken = $confirmationToken;
    }

    public function __toString(): string
    {
        return $this->confirmationToken;
    }
}
