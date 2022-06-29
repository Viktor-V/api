<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\List;

use App\Common\Application\Query\QueryInterface;

class ListQuery implements QueryInterface
{
    public function __construct(
        public readonly int $page,
        public readonly int $limit
    ) {
    }
}
