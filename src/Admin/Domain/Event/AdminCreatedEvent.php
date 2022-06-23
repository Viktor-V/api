<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Common\Domain\Event\EventInterface;

class AdminCreatedEvent implements EventInterface
{
    public function __construct(
        private Email $email,
        private Name $name
    ) {
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
