<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity\Embedded;

enum Status: string
{
    case ACTIVE = 'active';
    case DISABLED = 'disabled';
    case BLOCKED = 'blocked';
}
