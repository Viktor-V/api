<?php

declare(strict_types=1);

namespace App\AdminSecurity\Domain\ReadModel;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\DataTransfer\Admin;

interface AdminQueryInterface
{
    public function findByEmail(Email $email): ?Admin;
}
