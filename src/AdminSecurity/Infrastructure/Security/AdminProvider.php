<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Security;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\Entity\AdminIdentity;
use App\AdminSecurity\Domain\Repository\AdminIdentityRepositoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminProvider implements UserProviderInterface
{
    public function __construct(
        private AdminIdentityRepositoryInterface $repository
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $adminIdentity = $this->repository->findByEmail(new Email($identifier));

        if (!$adminIdentity) {
            throw new UserNotFoundException();
        }

        if ($adminIdentity->isActive() === false) {
            throw new UnsupportedUserException();
        }

        return $adminIdentity;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        $adminIdentity = $this->repository->findByEmail(new Email($user->getUserIdentifier()));

        if (!$adminIdentity) {
            throw new UserNotFoundException();
        }

        if ($adminIdentity->isActive() === false) {
            throw new UnsupportedUserException();
        }

        return $adminIdentity;
    }

    public function supportsClass(string $class): bool
    {
        return AdminIdentity::class === $class;
    }
}
