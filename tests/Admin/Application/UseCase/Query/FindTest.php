<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Query;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class FindTest extends ApplicationTestCase
{
    public function testAdminFoundByUuid(): void
    {
        $this->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            $email = 'admin@admin.com',
            $firstname = 'Admin',
            $lastname = 'Admin',
            'pswrd'
        ));
        /** @var Admin $admin */
        $admin = $this->handle(new FindQuery($uuid));

        self::assertNotNull($admin);
        self::assertEquals($uuid, $admin->getUuid());
        self::assertEquals($email, $admin->getEmail());
        self::assertEquals($firstname, $admin->getFirstname());
        self::assertEquals($lastname, $admin->getLastname());
        self::assertEquals('disabled', $admin->getStatus());
    }

    public function testAdminNotFoundByUuid(): void
    {
        self::assertNull($this->handle(new FindQuery(Uuid::v4()->__toString())));
    }
}
