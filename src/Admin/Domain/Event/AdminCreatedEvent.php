<?php

declare(strict_types=1);

namespace App\Admin\Domain\Event;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Common\Domain\Event\EventInterface;

final class AdminCreatedEvent implements EventInterface
{
    public function __construct(
        private Email $email,
        private Name $name,
        private PlainPassword $plainPassword,
        private ConfirmationToken $confirmationToken
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

    public function getConfirmationToken(): ConfirmationToken
    {
        return $this->confirmationToken;
    }
}
