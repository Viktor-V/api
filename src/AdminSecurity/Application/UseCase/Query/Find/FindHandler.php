<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\Find;

use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;

final class FindHandler implements QueryHandlerInterface
{
    public function __construct(
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function __invoke(FindQuery $query): ?Admin
    {
        return $this->adminQuery->find($query->uuid);
    }
}
