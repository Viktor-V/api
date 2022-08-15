<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\FindByEmail;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;

class FindByEmailHandler implements QueryHandlerInterface
{
    public function __construct(
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function __invoke(FindByEmailQuery $query): ?Admin
    {
        return $this->adminQuery->findByEmail($query->email);
    }
}
