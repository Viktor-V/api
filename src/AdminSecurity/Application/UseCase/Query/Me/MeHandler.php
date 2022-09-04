<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\Me;

use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;
use DomainException;

final class MeHandler implements QueryHandlerInterface
{
    public function __construct(
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function __invoke(MeQuery $query): ?Admin
    {
        $admin = $this->adminQuery->findByEmail($query->email);
        if ($admin === null) {
            throw new DomainException('Admin not found.');
        }

        return $admin;
    }
}
