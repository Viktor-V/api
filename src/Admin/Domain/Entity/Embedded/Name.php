<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity\Embedded;

use App\Common\Domain\Assert\Assertion;

final class Name
{
    public const MAX_NAME_LENGTH = 32;

    private string $firstname;
    private string $lastname;

    public function __construct(
        string $firstname,
        string $lastname,
    ) {
        Assertion::notEmpty($firstname, 'Firstname should not be empty.');
        Assertion::notEmpty($firstname, 'Lastname should not be empty.');

        Assertion::maxLength(
            $firstname,
            self::MAX_NAME_LENGTH,
            sprintf('Firstname must not exceed %d characters', self::MAX_NAME_LENGTH)
        );
        Assertion::maxLength(
            $lastname,
            self::MAX_NAME_LENGTH,
            sprintf('Lastname must not exceed %d characters', self::MAX_NAME_LENGTH)
        );

        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function __toString(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }
}
