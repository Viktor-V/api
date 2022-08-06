<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Command\Confirm;

use App\Common\Application\Command\CommandInterface;
use App\Common\Domain\Entity\Embedded\Uuid;

final class ConfirmCommand implements CommandInterface
{
    public readonly Uuid $uuid;

    public function __construct(
        string $uuid
    ) {
        $this->uuid = new Uuid($uuid);
    }
}
