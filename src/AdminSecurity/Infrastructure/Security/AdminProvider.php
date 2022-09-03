<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Security;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Admin\Domain\Entity\Embedded\Status;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AdminProvider implements UserProviderInterface
{
    public function __construct(
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $admin = $this->adminQuery->findByEmail(new Email($identifier));
        if (!$admin) {
            throw new UserNotFoundException();
        }

        $adminIdentity = new AdminIdentity(
            new Uuid($admin->getUuid()),
            new Email($admin->getEmail()),
            new Password($admin->getPassword()),
            Role::from($admin->getRole()),
            Status::from($admin->getStatus()),
        );

        if ($adminIdentity->isActive() === false) {
            throw new UserNotFoundException();
        }

        return $adminIdentity;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return AdminIdentity::class === $class;
    }
}
