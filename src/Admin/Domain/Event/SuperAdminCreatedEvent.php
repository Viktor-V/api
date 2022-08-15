<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Common\Domain\Event\EventInterface;

final class SuperAdminCreatedEvent implements EventInterface
{
    public function __construct(
        private Email $email,
        private Name $name,
        private PlainPassword $plainPassword
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

    public function getPlainPassword(): PlainPassword
    {
        return $this->plainPassword;
    }
}
