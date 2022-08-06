<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\ReadModel;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
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
            ->setPassword((string) $row['password'])
            ->setStatus((string) $row['status'])
            ->setRole((string) $row['role'])
            ->setCreatedAt((string) $row['created_at'])
            ->setUpdatedAt((string) $row['updated_at']);
    }

    public function findByConfirmationToken(ConfirmationToken $token): ?Admin
    {
        $this->queryBuilder
            ->select('*')
            ->from('admin')
            ->where('confirmation_token = :confirmationToken')
            ->setParameter('confirmationToken', $token->__toString());

        $row = $this->queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return (new Admin())
            ->setUuid((string) $row['uuid'])
            ->setEmail((string) $row['email'])
            ->setFirstname((string) $row['firstname'])
            ->setLastname((string) $row['lastname'])
            ->setPassword((string) $row['password'])
            ->setStatus((string) $row['status'])
            ->setRole((string) $row['role'])
            ->setCreatedAt((string) $row['created_at'])
            ->setUpdatedAt((string) $row['updated_at'])
            ->setConfirmationToken((string) $row['confirmation_token']);
    }
}
