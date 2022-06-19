<?php

declare(strict_types=1);

namespace App\Admin\Domain\ValueObject;

use App\Common\Domain\Assert\Assertion;

final class Email
{
    public const MAX_EMAIL_LENGTH = 100;

    private string $email;

    public function __construct(
        string $email
    ) {
        Assertion::notEmpty($email, 'Email should not be empty.');
        Assertion::maxLength(
            $email,
            self::MAX_EMAIL_LENGTH,
            sprintf('Email must not exceed %d characters', self::MAX_EMAIL_LENGTH)
        );
        Assertion::email($email);

        $this->email = $email;
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
