<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity;

use App\Admin\Domain\ValueObject\ConfirmationToken;
use App\Admin\Domain\ValueObject\Email;
use App\Admin\Domain\ValueObject\Name;
use App\Admin\Domain\ValueObject\Password;
use App\Admin\Domain\ValueObject\Role;
use App\Admin\Domain\ValueObject\Status;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Entity\Aggregate;
use App\Common\Domain\Specification\SpecificationInterface;
use App\Common\Domain\ValueObject\Uuid;
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
        $this->specification->isSatisfied();

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
        $admin->raise(new AdminCreatedEvent($uuid, $email, $name, $role));

        return $admin;
    }

    public static function signUp(
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
        $admin->raise(new AdminCreatedEvent($uuid, $email, $name, $role));

        return $admin;
    }
}