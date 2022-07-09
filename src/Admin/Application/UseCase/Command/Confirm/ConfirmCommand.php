<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Confirm;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Common\Application\Command\CommandInterface;

final class ConfirmCommand implements CommandInterface
{
    public readonly ConfirmationToken $token;

    public function __construct(
        string $token
    ) {
        $this->token = new ConfirmationToken($token);
    }
}
