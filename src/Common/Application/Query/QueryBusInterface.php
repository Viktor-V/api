<?php

declare(strict_types=1);

namespace App\Common\Application\Query;

interface QueryBusInterface
{
    public function handle(QueryInterface $query): mixed;
}
