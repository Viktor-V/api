<?php

declare(strict_types=1);

namespace App\Admin\Domain\ValueObject;

enum Role
{
    case ROLE_ADMIN;
    case ROLE_SUPER_ADMIN;
}
