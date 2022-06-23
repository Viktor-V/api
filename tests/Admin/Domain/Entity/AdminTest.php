<?php

declare(strict_types=1);

namespace App\Tests\Admin\Domain\Entity;

use App\Admin\Domain\Entity\Admin;
use App\Admin\Domain\Entity\Embedded\ConfirmationToken;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\Password;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Admin\Domain\Event\AdminRegisteredEvent;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Common\Domain\Specification\SpecificationInterface;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    public function testCreate(): void
    {
        $admin = Admin::create(
            new Uuid('b48b643e-a9b8-41a6-802d-0b438b566f62'),
            new Email('admin@admin.com'),
            new Name('Firstname', 'Lastname'),
            new PlainPassword('qwert'),
            new Password('qwert'),
            $this->createMock(SpecificationInterface::class)
        );

        self::assertInstanceOf(Admin::class, $admin);

        $events = [];
        foreach ($admin->popEvents() as $event) {
            $events[] = $event::class;
        }

        self::assertContains(AdminCreatedEvent::class, $events);
    }

    public function testSignUp(): void
    {
        $admin = Admin::register(
            new Uuid('b48b643e-a9b8-41a6-802d-0b438b566f62'),
            new Email('admin@admin.com'),
            new Name('Firstname', 'Lastname'),
            new PlainPassword('qwert'),
            new Password('qwert'),
            $this->createMock(SpecificationInterface::class),
            new ConfirmationToken('token')
        );

        self::assertInstanceOf(Admin::class, $admin);

        $events = [];
        foreach ($admin->popEvents() as $event) {
            $events[] = $event::class;
        }

        self::assertContains(AdminRegisteredEvent::class, $events);
    }
}
