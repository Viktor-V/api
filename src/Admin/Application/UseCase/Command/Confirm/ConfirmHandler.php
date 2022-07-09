<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Confirm;

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
        $admin = $this->adminRepository->findByConfirmationToken($command->token);

        if ($admin === null) {
            throw new DomainException('Incorrect or confirmed token.');
        }

        $admin->confirm();
        $this->adminRepository->save($admin);
    }
}
