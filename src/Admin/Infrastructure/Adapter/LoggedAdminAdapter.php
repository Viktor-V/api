<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Adapter;

use App\AdminSecurity\Infrastructure\Security\AdminIdentity;
use Symfony\Component\Security\Core\Security;

class LoggedAdminAdapter
{
    public function __construct(
        private Security $security
    ) {
    }

    public function getLoggedAdminUuid(): string
    {
        /** @var AdminIdentity $adminIdentity */
        $adminIdentity = $this->security->getUser();

        return $adminIdentity->getUuid();
    }
}
