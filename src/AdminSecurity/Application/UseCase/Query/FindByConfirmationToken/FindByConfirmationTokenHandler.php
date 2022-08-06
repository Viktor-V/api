<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\FindByConfirmationToken;

use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;

final class FindByConfirmationTokenHandler implements QueryHandlerInterface
{
    public function __construct(
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function __invoke(FindByConfirmationTokenQuery $query): ?Admin
    {
        return $this->adminQuery->findByConfirmationToken($query->token);
    }
}
