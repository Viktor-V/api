<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\ReadModel\DoctrineQuery;

trait CountTrait
{
    public function fetchCount(): int
    {
        $this->queryBuilder
            ->resetQueryPart('orderBy')
            ->setFirstResult(0)
            ->setMaxResults(null);

        return (int) $this->queryBuilder
            ->select('COUNT(*)')
            ->executeQuery()
            ->fetchOne();
    }
}
