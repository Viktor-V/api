<?php

declare(strict_types=1);

namespace App\Security\Domain\Entity;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\Role;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminIdentity implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private Email $email,
        private Password $password,
        private Role $role
    ) {
    }

    public function getRoles(): array
    {
        return [$this->role->value];
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return $this->email->__toString();
    }

    public function getPassword(): ?string
    {
        return $this->password->__toString();
    }
}
