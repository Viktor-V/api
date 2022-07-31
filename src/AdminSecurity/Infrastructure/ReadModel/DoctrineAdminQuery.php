<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\ReadModel;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Infrastructure\ReadModel\AbstractDoctrineQuery;

class DoctrineAdminQuery extends AbstractDoctrineQuery implements AdminQueryInterface
{
    public function findByEmail(Email $email): ?Admin
    {
        $this->queryBuilder
            ->select('*')
            ->from('admin')
            ->andWhere('email = :email')
            ->setParameter('email', $email->__toString());

        $row = $this->queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return new Admin(
            (string) $row['uuid'],
            (string) $row['email'],
            (string) $row['firstname'],
            (string) $row['lastname'],
            (string) $row['password'],
            (string) $row['status'],
            (string) $row['role'],
            (string) $row['created_at'],
            (string) $row['updated_at']
        );
    }
}
