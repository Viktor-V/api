<?php

declare(strict_types=1);

namespace App\AdminSecurity\Domain\Repository;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\Entity\AdminIdentity;

interface AdminIdentityRepositoryInterface
{
    public function findByEmail(Email $email): ?AdminIdentity;
}
