<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\Create;

use App\Admin\Application\Service\PasswordEncoderInterface;
use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Domain\Specification\SpecificationInterface;

final class CreateHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordEncoderInterface $passwordEncoder,
        private SpecificationInterface $specification,
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function __invoke(CreateCommand $command): void
    {
        $this->adminRepository->save(
            Admin::create(
                $command->uuid,
                $command->email,
                $command->name,
                $this->passwordEncoder->encode($command->password),
                $this->specification
            )
        );
    }
}
