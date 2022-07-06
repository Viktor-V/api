<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\ReadModel\DoctrineQuery;

use Doctrine\DBAL\Query\QueryBuilder;

trait PagingTrait
{
    public function paginate(int $page, int $limit): QueryBuilder
    {
        $this->queryBuilder->setFirstResult(($page - 1) * $limit);
        $this->queryBuilder->setMaxResults($limit);

        return $this->queryBuilder;
    }
}
