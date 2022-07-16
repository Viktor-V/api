<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Update;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Domain\Specification\SpecificationInterface;
use DomainException;

final class UpdateHandler implements CommandHandlerInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private SpecificationInterface $specification,
    ) {
    }

    public function __invoke(UpdateCommand $command): void
    {
        $admin = $this->adminRepository->findByUuid($command->uuid);

        if ($admin === null) {
            throw new DomainException(sprintf('Admin not found. Uuid: #%s', $command->uuid->__toString()));
        }

        $admin->update($command->email, $command->name, $this->specification);
        $this->adminRepository->save($admin);
    }
}
