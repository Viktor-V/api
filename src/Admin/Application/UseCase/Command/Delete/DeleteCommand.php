<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Delete;

use App\Common\Application\Command\CommandInterface;
use App\Common\Domain\Entity\Embedded\Uuid;

final class DeleteCommand implements CommandInterface
{
    public readonly Uuid $uuid;

    public function __construct(
        string $uuid
    ) {
        $this->uuid = new Uuid($uuid);
    }
}
