<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\ReadModel;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
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

        return $this->initializeAdmin($row);
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

        return $this->initializeAdmin($row);
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

        return $this->initializeAdmin($row);
    }

    private function initializeAdmin(array $data): Admin
    {
        return (new Admin())
            ->setUuid((string) $data['uuid'])
            ->setEmail((string) $data['email'])
            ->setFirstname((string) $data['firstname'])
            ->setLastname((string) $data['lastname'])
            ->setPassword((string) $data['password'])
            ->setStatus((string) $data['status'])
            ->setRole((string) $data['role'])
            ->setCreatedAt((string) $data['created_at'])
            ->setUpdatedAt((string) $data['updated_at'])
            ->setConfirmationToken((string) $data['confirmation_token']);
    }
}
