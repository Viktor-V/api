<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Activate;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use DomainException;

final class ActivateHandler implements CommandHandlerInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(ActivateCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if ($admin === null) {
            throw new DomainException(sprintf('Admin not found. Uuid: #%s', $command->uuid->__toString()));
        }

        $admin->activate();
        $this->adminRepository->save($admin);
    }
}
