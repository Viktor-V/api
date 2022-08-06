<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\FindByConfirmationToken;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Common\Application\Query\QueryInterface;

final class FindByConfirmationTokenQuery implements QueryInterface
{
    public readonly ConfirmationToken $token;

    public function __construct(
        string $token
    ) {
        $this->token = new ConfirmationToken($token);
    }
}
