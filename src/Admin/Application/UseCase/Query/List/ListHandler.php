<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\List;

use App\Admin\Application\DataTransfer\Admin;
use App\Common\Application\Query\QueryHandlerInterface;
use Doctrine\DBAL\Connection;
use Generator;

class ListHandler implements QueryHandlerInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function __invoke(ListQuery $query): Generator
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('admin');

        $queryBuilder->setFirstResult(($query->page - 1) * $query->limit);
        $queryBuilder->setMaxResults($query->limit);

        $rows = $queryBuilder->executeQuery()->fetchAllAssociative();
        foreach ($rows as $row) {
            yield Admin::initialization(
                (string) $row['uuid'],
                (string) $row['email'],
                (string) $row['firstname'],
                (string) $row['lastname'],
                (string) $row['password']
            );
        }

        $queryBuilder
            ->resetQueryPart('orderBy')
            ->setFirstResult(0)
            ->setMaxResults(null);

        return (int) $queryBuilder
            ->select('COUNT(*)')
            ->from('admin')
            ->executeQuery()
            ->fetchOne();
    }
}
