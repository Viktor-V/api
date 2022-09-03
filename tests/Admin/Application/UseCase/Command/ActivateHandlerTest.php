<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use DomainException;

class ActivateHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulAdminActivated(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new ActivateCommand($uuid));

        /** @var Admin $admin */
        $admin = $this->handle(new FindQuery($uuid));
        self::assertEquals($admin->getStatus(), Status::ACTIVATED->value);
    }

    public function testAdminAlreadyActivated(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new ActivateCommand($uuid));

        try {
            $this->dispatch(new ActivateCommand($uuid));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(DomainException::class, $exception->getPrevious());
            self::assertEquals("Admin is already activated.", $exception->getPrevious()->getMessage());
        }
    }
}
