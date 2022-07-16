<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Block;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use DomainException;

final class BlockHandler implements CommandHandlerInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(BlockCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if ($admin === null) {
            throw new DomainException(sprintf('Admin not found. Uuid: #%s', $command->uuid->__toString()));
        }

        $admin->block();
        $this->adminRepository->save($admin);
    }
}
