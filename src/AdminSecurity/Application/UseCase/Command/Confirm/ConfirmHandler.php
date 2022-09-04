<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Command\Confirm;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use DomainException;

final class ConfirmHandler implements CommandHandlerInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(ConfirmCommand $command): void
    {
        if ($command->loggedIn) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $admin = $this->adminRepository->findByUuid($command->uuid);
        if ($admin === null) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $admin->activate();
        $this->adminRepository->save($admin);
    }
}
