<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Repository;

use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Infrastructure\Repository\AbstractDoctrineRepository;

class DoctrineAdminRepository extends AbstractDoctrineRepository implements AdminRepositoryInterface
{
    protected const CLASS_NAME = Admin::class;

    public function save(Admin $admin): void
    {
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }

    public function findByEmail(Email $email): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(['email.email' => $email->__toString()]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }

    public function findByConfirmationToken(ConfirmationToken $token): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(['confirmationToken.confirmationToken' => $token->__toString()]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }
}
