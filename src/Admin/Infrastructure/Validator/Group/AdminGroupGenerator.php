<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Validator\Group;

use ApiPlatform\Core\Bridge\Symfony\Validator\ValidationGroupsGeneratorInterface;
use App\Admin\Domain\DataTransfer\Admin;
use Symfony\Component\Security\Core\Security;
use RuntimeException;

final class AdminGroupGenerator implements ValidationGroupsGeneratorInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    /**
     * @param Admin $object
     */
    public function __invoke($object): array
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser) {
            throw new RuntimeException('User is not logged in.');
        }

        return $currentUser->getUserIdentifier() === $object->getEmail()
            ? ['update', 'update_by_self']
            : ['update'];
    }
}
