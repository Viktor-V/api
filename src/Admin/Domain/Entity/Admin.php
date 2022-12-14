<?php

declare(strict_types=1);

namespace App\Admin\Domain\Entity;

use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Domain\Entity\Embedded\Role;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Admin\Domain\Event\AdminPasswordUpdatedEvent;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Common\Domain\Entity\Aggregate;
use App\Common\Domain\Specification\SpecificationInterface;
use DomainException;
use DateTimeImmutable;

class Admin extends Aggregate
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

    public static function createSuper(
        Uuid $uuid,
        Email $email,
        Name $name,
        PlainPassword $plainPassword,
        Password $password,
        SpecificationInterface $specification
    ): self {
        $role = Role::ROLE_SUPER_ADMIN;
        $status = Status::ACTIVATED;

        $admin = new self($uuid, $email, $name, $password, $role, $status, $specification);
        $admin->raise(new SuperAdminCreatedEvent($email, $name, $plainPassword));

        return $admin;
    }

    public static function create(
        Uuid $uuid,
        Email $email,
        Name $name,
        PlainPassword $plainPassword,
        Password $password,
        SpecificationInterface $specification,
        ConfirmationToken $confirmationToken
    ): self {
        $role = Role::ROLE_ADMIN;
        $status = Status::DISABLED;

        $admin = new self($uuid, $email, $name, $password, $role, $status, $specification, $confirmationToken);
        $admin->raise(new AdminCreatedEvent($email, $name, $plainPassword, $confirmationToken));

        return $admin;
    }

    public function update(
        Email $email,
        Name $name,
        SpecificationInterface $specification
    ): void {
        if ($this->email->__toString() !== $email->__toString()) {
            $specification->isSatisfied($email);
            $this->email = $email;
        }

        if ($this->name->__toString() !== $name->__toString()) {
            $this->name = $name;
        }

        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        if ($this->status === Status::ACTIVATED) {
            throw new DomainException('Admin is already activated.');
        }

        $this->status = Status::ACTIVATED;
        $this->confirmationToken = null;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function block(): void
    {
        if ($this->status === Status::BLOCKED) {
            throw new DomainException('Admin is already blocked.');
        }

        $this->status = Status::BLOCKED;
        $this->confirmationToken = null;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function updatePassword(
        PlainPassword $plainPassword,
        Password $password,
        Uuid $changedBy
    ): void {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();

        if ($this->uuid->__toString() !== $changedBy->__toString()) {
            $this->raise(new AdminPasswordUpdatedEvent($this->email, $this->name, $plainPassword));
        }
    }
}
