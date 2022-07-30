<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Repository;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Infrastructure\Security\AdminIdentity;

interface AdminIdentityRepositoryInterface
{
    public function findByEmail(Email $email): ?AdminIdentity;
}
