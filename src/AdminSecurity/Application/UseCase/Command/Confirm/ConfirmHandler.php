<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Command\Confirm;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use Symfony\Component\Security\Core\Security;
use DomainException;

final class ConfirmHandler implements CommandHandlerInterface
{
    public function __construct(
        private Security $security,
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(ConfirmCommand $command): void
    {
        $user = $this->security->getUser();
        if ($user !== null) {
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
