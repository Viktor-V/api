<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\Security;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Security\Domain\Entity\AdminIdentity;
use App\Security\Domain\Repository\AdminIdentityRepositoryInterface;
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

        return $adminIdentity;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->repository->findByEmail(new Email($user->getUserIdentifier()));
    }

    public function supportsClass(string $class): bool
    {
        return AdminIdentity::class === $class;
    }
}
