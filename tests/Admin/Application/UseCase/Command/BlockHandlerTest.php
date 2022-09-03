<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use DomainException;

class BlockHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulAdminBlocked(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new BlockCommand($uuid));

        /** @var Admin $admin */
        $admin = $this->handle(new FindQuery($uuid));
        self::assertEquals($admin->getStatus(), Status::BLOCKED->value);
    }

    public function testAdminAlreadyBlocked(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new BlockCommand($uuid));

        try {
            $this->dispatch(new BlockCommand($uuid));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(DomainException::class, $exception->getPrevious());
            self::assertEquals("Admin is already blocked.", $exception->getPrevious()->getMessage());
        }
    }
}
