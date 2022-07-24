<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Repository;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Security\Domain\Entity\AdminIdentity;
use App\Security\Domain\Repository\AdminIdentityRepositoryInterface;
use Doctrine\DBAL\Connection;

class DoctrineAdminIdentityRepository implements AdminIdentityRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function findByEmail(Email $email): ?AdminIdentity
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->select('role', 'password', 'status')
            ->from('admin')
            ->andWhere('email = :email')
            ->setParameter('email', $email->__toString());

        $row = $queryBuilder->executeQuery()->fetchAssociative();
        if ($row === false) {
            return null;
        }

        return new AdminIdentity(
            $email,
            new Password((string) $row['password']),
            Role::from((string) $row['role']),
            Status::from((string) $row['status']),
        );
    }
}
