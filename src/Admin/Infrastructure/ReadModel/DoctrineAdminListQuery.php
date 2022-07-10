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
            yield (new Admin())
                ->setUuid((string) $row['uuid'])
                ->setEmail((string) $row['email'])
                ->setFirstname((string) $row['firstname'])
                ->setLastname((string) $row['lastname'])
                ->setStatus((string) $row['status'])
                ->setCreatedAt((string) $row['created_at'])
                ->setUpdatedAt((string) $row['updated_at']);
        }

        return $this->fetchCount();
    }
}
