<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Repository;

use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Common\Infrastructure\Repository\AbstractDoctrineRepository;

class DoctrineAdminRepository extends AbstractDoctrineRepository implements AdminRepositoryInterface
{
    protected const CLASS_NAME = Admin::class;

    public function save(Admin $admin): void
    {
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }

    public function delete(Admin $admin): void
    {
        $this->entityManager->remove($admin);
        $this->entityManager->flush();
    }

    public function findByUuid(Uuid $uuid): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(['uuid.uuid' => $uuid->__toString()]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }

    public function findByEmail(Email $email): ?Admin
    {
        $admin = $this->objectRepository->findOneBy(['email.email' => $email->__toString()]);

        if (!$admin instanceof Admin) {
            return null;
        }

        return $admin;
    }
}
