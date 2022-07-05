<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\Find;

use App\Admin\Domain\DataTransfer\Admin;
use App\Common\Application\Query\QueryHandlerInterface;
use Doctrine\DBAL\Connection;

class FindHandler implements QueryHandlerInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function __invoke(FindQuery $query): ?Admin
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('admin')
            ->andWhere('uuid = :uuid')
            ->setParameter('uuid', $query->uuid->__toString());

        $row = $queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return new Admin(
            (string) $row['uuid'],
            (string) $row['email'],
            (string) $row['firstname'],
            (string) $row['lastname'],
            (string) $row['password']
        );
    }
}
