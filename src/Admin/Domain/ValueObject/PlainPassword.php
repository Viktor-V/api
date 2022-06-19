<?php

declare(strict_types=1);

namespace App\Admin\Domain\ValueObject;

use App\Common\Domain\Assert\Assertion;

final class PlainPassword
{
    public const MIN_PASSWORD_LENGTH = 5;

    private string $password;

    public function __construct(
        string $password
    ) {
        Assertion::notEmpty($password, 'Password should not be empty.');
        Assertion::minLength(
            $password,
            self::MIN_PASSWORD_LENGTH,
            sprintf('Password must be at least %d characters long.', self::MIN_PASSWORD_LENGTH)
        );

        $this->password = $password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}
