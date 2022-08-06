<?php

declare(strict_types=1);

namespace App\AdminSecurity\Domain\DataTransfer;

use App\Admin\Domain\DataTransfer\AdminTrait;

final class Admin
{
    use AdminTrait;

    private string $role = '';

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }
}
