<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\Find;

use App\Common\Application\Query\QueryInterface;
use App\Common\Domain\Entity\Embedded\Uuid;

final class FindQuery implements QueryInterface
{
    public readonly Uuid $uuid;

    public function __construct(
        string $uuid
    ) {
        $this->uuid = new Uuid($uuid);
    }
}
