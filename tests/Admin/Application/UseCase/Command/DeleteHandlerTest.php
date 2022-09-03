<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use DomainException;

class DeleteHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulAdminDeleted(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new DeleteCommand($uuid));

        self::assertNull($this->handle(new FindQuery($uuid)));
    }

    public function testAdminCannotBeDeletedAsItIsNotExists(): void
    {
        $uuid = Uuid::v4()->__toString();

        try {
            $this->dispatch(new DeleteCommand($uuid));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(DomainException::class, $exception->getPrevious());
            self::assertEquals(
                "Admin not found. Uuid: #${uuid}",
                $exception->getPrevious()->getMessage()
            );
        }
    }
}
