<?php

declare(strict_types=1);

namespace App\Admin\Domain\ReadModel;

use Generator;

interface AdminListQueryInterface
{
    public function fetch(int $page, int $limit): Generator;
}
