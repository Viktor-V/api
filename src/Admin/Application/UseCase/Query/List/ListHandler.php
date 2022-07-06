<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\List;

use App\Admin\Domain\ReadModel\AdminListQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;
use Generator;

class ListHandler implements QueryHandlerInterface
{
    public function __construct(
        private AdminListQueryInterface $adminListQuery
    ) {
    }

    public function __invoke(ListQuery $query): Generator
    {
        return $this->adminListQuery->fetch($query->page, $query->limit);
    }
}
