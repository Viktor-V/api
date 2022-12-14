<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Query;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class FindByEmailTest extends ApplicationTestCase
{
    public function testFoundByEMail(): void
    {
        $this->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            $email = 'admin@admin.com',
            $firstname = 'Admin',
            $lastname = 'Admin',
            'pswrd'
        ));
        /** @var Admin $admin */
        $admin = $this->handle(new FindByEmailQuery($email));

        self::assertNotNull($admin);
        self::assertEquals($uuid, $admin->getUuid());
        self::assertEquals($email, $admin->getEmail());
        self::assertEquals($firstname, $admin->getFirstname());
        self::assertEquals($lastname, $admin->getLastname());
        self::assertEquals('disabled', $admin->getStatus());
    }

    public function testAdminNotFoundByEmail(): void
    {
        self::assertNull($this->handle(new FindByEmailQuery('admin@admin.com')));
    }
}
