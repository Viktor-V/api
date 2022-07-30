<?php

declare(strict_types=1);

namespace App\Security\Domain\Entity;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Admin\Domain\Entity\Embedded\Status;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminIdentity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private Email $email,
        private Password $password,
        private Role $role,
        private Status $status
    ) {
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function eraseCredentials(): void
    {
        $this->password = new Password('secret');
    }

    public function getUserIdentifier(): string
    {
        return $this->email->__toString();
    }

    public function getPassword(): ?string
    {
        return $this->password->__toString();
    }

    public function isActive(): bool
    {
        return Status::ACTIVATED === $this->status;
    }
}
