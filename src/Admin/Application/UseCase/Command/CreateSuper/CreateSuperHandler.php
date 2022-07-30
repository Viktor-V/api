<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\CreateSuper;

use App\Admin\Application\Service\PasswordEncoderInterface;
use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Domain\Specification\SpecificationInterface;

final class CreateSuperHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordEncoderInterface $passwordEncoder,
        private SpecificationInterface $specification,
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(CreateSuperCommand $command): void
    {
        $this->adminRepository->save(
            Admin::createSuper(
                $command->uuid,
                $command->email,
                $command->name,
                $command->password,
                $this->passwordEncoder->encode($command->password),
                $this->specification
            )
        );
    }
}
