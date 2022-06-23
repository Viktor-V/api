<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Admin\Domain\Event\AdminRegisteredEvent;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Entity\Aggregate;
use App\Common\Domain\Specification\SpecificationInterface;
use DateTimeImmutable;

final class Admin extends Aggregate
{
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    private function __construct(
        private Uuid $uuid,
        private Email $email,
        private Name $name,
        private Password $password,
        private Role $role,
        private Status $status,
        private SpecificationInterface $specification,
        private ?ConfirmationToken $confirmationToken = null
    ) {
        $this->specification->isSatisfied($email);

        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = null;
    }

    public static function create(
        Uuid $uuid,
        Email $email,
        Name $name,
        Password $password,
        SpecificationInterface $specification
    ): self {
        $role = Role::ROLE_SUPER_ADMIN;
        $status = Status::ACTIVE;

        $admin = new self($uuid, $email, $name, $password, $role, $status, $specification);
        $admin->raise(new AdminCreatedEvent($email, $name));

        return $admin;
    }

    public static function register(
        Uuid $uuid,
        Email $email,
        Name $name,
        Password $password,
        SpecificationInterface $specification,
        ConfirmationToken $confirmationToken
    ): self {
        $role = Role::ROLE_ADMIN;
        $status = Status::DISABLED;

        $admin = new self($uuid, $email, $name, $password, $role, $status, $specification, $confirmationToken);
        $admin->raise(new AdminRegisteredEvent($email, $name, $confirmationToken));

        return $admin;
    }
}
