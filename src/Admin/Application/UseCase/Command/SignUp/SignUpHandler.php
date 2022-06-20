<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Command\SignUp;

use App\Admin\Application\Service\ConfirmationTokenGeneratorInterface;
use App\Admin\Application\Service\PasswordEncoderInterface;
use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Common\Application\Command\CommandHandlerInterface;
use App\Common\Domain\Specification\SpecificationInterface;

final class SignUpHandler implements CommandHandlerInterface
{
    public function __construct(
        private PasswordEncoderInterface $passwordEncoder,
        private SpecificationInterface $specification,
        private AdminRepositoryInterface $adminRepository,
        private ConfirmationTokenGeneratorInterface $confirmationTokenGenerator
    ) {
    }

    public function __invoke(SignUpCommand $command): void
    {
        $this->adminRepository->save(
            Admin::signUp(
                $command->uuid,
                $command->email,
                $command->name,
                $this->passwordEncoder->encode($command->password),
                $this->specification,
                new ConfirmationToken($this->confirmationTokenGenerator->generate())
            )
        );
    }
}
