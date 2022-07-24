<?php

declare(strict_types=1);

namespace App\Security\Domain\Repository;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Security\Domain\Entity\AdminIdentity;

interface AdminIdentityRepositoryInterface
{
    public function findByEmail(Email $email): ?AdminIdentity;
}
