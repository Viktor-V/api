<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event;

use App\Admin\Domain\ValueObject\Email;
use App\Admin\Domain\ValueObject\Name;
use App\Admin\Domain\ValueObject\Role;
use App\Common\Domain\Event\EventInterface;
use App\Common\Domain\ValueObject\Uuid;

class AdminCreatedEvent implements EventInterface
{
    public function __construct(
        private Uuid $uuid,
        private Email $email,
        private Name $name,
        private Role $role
    ) {
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getRole(): Role
    {
        return $this->role;
    }
}
