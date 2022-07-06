<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\ReadModel;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Common\Infrastructure\ReadModel\AbstractDoctrineQuery;

class DoctrineAdminQuery extends AbstractDoctrineQuery implements AdminQueryInterface
{
    public function find(Uuid $uuid): ?Admin
    {
        $this->queryBuilder
            ->select('*')
            ->from('admin')
            ->andWhere('uuid = :uuid')
            ->setParameter('uuid', $uuid->__toString());

        $row = $this->queryBuilder->executeQuery()->fetchAssociative();
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
