<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Common\Domain\Event\EventInterface;

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
