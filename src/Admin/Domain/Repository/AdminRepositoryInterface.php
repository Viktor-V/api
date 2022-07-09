<?php

declare(strict_types=1);

namespace App\Admin\Domain\Repository;

use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;

interface AdminRepositoryInterface
{
    public function save(Admin $admin): void;
    public function findByEmail(Email $email): ?Admin;
    public function findByConfirmationToken(ConfirmationToken $token): ?Admin;
}
