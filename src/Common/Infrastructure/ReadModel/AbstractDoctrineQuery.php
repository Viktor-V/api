<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\ReadModel;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

abstract class AbstractDoctrineQuery
{
    protected QueryBuilder $queryBuilder;

    public function __construct(
        protected Connection $connection
    ) {
        $this->queryBuilder = $this->connection->createQueryBuilder();
    }
}
