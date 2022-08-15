<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\ReadModel;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\Entity\Embedded\Email;
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
            ->where('uuid = :uuid')
            ->setParameter('uuid', $uuid->__toString());

        $row = $this->queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return (new Admin())
            ->setUuid((string) $row['uuid'])
            ->setEmail((string) $row['email'])
            ->setFirstname((string) $row['firstname'])
            ->setLastname((string) $row['lastname'])
            ->setStatus((string) $row['status'])
            ->setCreatedAt((string) $row['created_at'])
            ->setUpdatedAt((string) $row['updated_at']);
    }

    public function findByEmail(Email $email): ?Admin
    {
        $this->queryBuilder
            ->select('*')
            ->from('admin')
            ->where('email = :email')
            ->setParameter('email', $email->__toString());

        $row = $this->queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return (new Admin())
            ->setUuid((string) $row['uuid'])
            ->setEmail((string) $row['email'])
            ->setFirstname((string) $row['firstname'])
            ->setLastname((string) $row['lastname'])
            ->setStatus((string) $row['status'])
            ->setPassword((string) $row['password'])
            ->setCreatedAt((string) $row['created_at'])
            ->setUpdatedAt((string) $row['updated_at']);
    }
}
