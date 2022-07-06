<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\ReadModel;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminListQueryInterface;
use App\Common\Infrastructure\ReadModel\AbstractDoctrineQuery;
use App\Common\Infrastructure\ReadModel\DoctrineQuery\CountTrait;
use App\Common\Infrastructure\ReadModel\DoctrineQuery\PagingTrait;
use Generator;

class DoctrineAdminListQuery extends AbstractDoctrineQuery implements AdminListQueryInterface
{
    use PagingTrait;
    use CountTrait;

    public function fetch(int $page, int $limit): Generator
    {
        $this->queryBuilder = $this->connection->createQueryBuilder();
        $this->queryBuilder
            ->select('*')
            ->from('admin');

        $this->paginate($page, $limit);

        $rows = $this->queryBuilder->executeQuery()->fetchAllAssociative();
        foreach ($rows as $row) {
            yield new Admin(
                (string) $row['uuid'],
                (string) $row['email'],
                (string) $row['firstname'],
                (string) $row['lastname'],
                (string) $row['password']
            );
        }

        return $this->fetchCount();
    }
}
