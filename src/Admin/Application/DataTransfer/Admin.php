<?php

declare(strict_types=1);

namespace App\Admin\Application\DataTransfer;

use App\Common\Application\DataTransfer\UuidTrait;

class Admin
{
    use UuidTrait;

    public function __construct(
        public readonly string $email,
        public readonly string $firstname,
        public readonly string $lastname,
        public readonly string $password
    ) {
    }

    public static function initialization(
        string $uuid,
        string $email,
        string $firstname,
        string $lastname,
        string $password
    ): self {
        $admin = new self($email, $firstname, $lastname, $password);

        $admin->uuid = $uuid;

        return $admin;
    }
}
