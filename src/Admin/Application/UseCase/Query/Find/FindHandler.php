<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\Find;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;

class FindHandler implements QueryHandlerInterface
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
