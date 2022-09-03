<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Domain\Event\AdminPasswordUpdatedEvent;
use App\Common\Domain\Specification\SpecificationException;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;
use DomainException;

class UpdateHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulAdminUpdated(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        /** @var Admin $admin */
        $admin = $this->handle(new FindQuery($uuid));

        $this->dispatch(new UpdateCommand(
            $uuid,
            'new@new.com',
            'New',
            'New',
            $uuid,
            'newpswrd'
        ));

        /** @var Admin $updatedAdmin */
        $updatedAdmin = $this->handle(new FindQuery($uuid));

        self::assertEquals($admin->getUuid(), $updatedAdmin->getUuid());
        self::assertNotEquals($admin->getEmail(), $updatedAdmin->getEmail());
        self::assertNotEquals($admin->getFirstname(), $updatedAdmin->getFirstname());
        self::assertNotEquals($admin->getLastname(), $updatedAdmin->getLastname());
    }

    public function testAdminCannotBeUpdatedAsIsNotExists(): void
    {
        $uuid = Uuid::v4()->__toString();

        try {
            $this->dispatch(new UpdateCommand(
                $uuid,
                'new@new.com',
                'New',
                'New',
                $uuid,
                'newpswrd'
            ));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(\DomainException::class, $exception->getPrevious());
            self::assertEquals(
                "Admin not found. Uuid: #${uuid}",
                $exception->getPrevious()->getMessage()
            );
        }
    }

    public function testAdminCannotBeUpdatedAsEmailAlreadyUsed(): void
    {
        $this->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new CreateCommand(
            Uuid::v4()->__toString(),
            'new@new.com',
            'New',
            'New',
            'pswrd'
        ));

        try {
            $this->dispatch(new UpdateCommand(
                $uuid,
                'new@new.com',
                'New',
                'New',
                $uuid,
                'newpswrd'
            ));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(SpecificationException::class, $exception->getPrevious());
            self::assertEquals(
                'Admin already exists with such email new@new.com.',
                $exception->getPrevious()->getMessage()
            );
        }
    }

    public function testUpdatePasswordBySelf(): void
    {
        $uuid = Uuid::v4()->__toString();

        $this->dispatch(new CreateCommand(
            $uuid,
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->process();

        $this->dispatch(new UpdateCommand(
            $uuid,
            'new@new.com',
            'New',
            'New',
            $uuid,
            'newpswrd'
        ));

        $this->messenger('async')->queue()->assertEmpty();
    }

    public function testUpdatePasswordByAnotherAdmin(): void
    {
        $this->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $this->dispatch(new CreateCommand(
            $superUuid = Uuid::v4()->__toString(),
            'super@super.com',
            'Super',
            'Super',
            'pswrd'
        ));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->process();

        $this->dispatch(new UpdateCommand(
            $uuid,
            $email = 'new@new.com',
            $firstname = 'New',
            $lastname = 'New',
            $superUuid,
            $password = 'newpswrd'
        ));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(AdminPasswordUpdatedEvent::class);

        $messages = $this->messenger('async')->queue()->messages(AdminPasswordUpdatedEvent::class);
        /** @var AdminPasswordUpdatedEvent $adminPasswordUpdatedEvent */
        $adminPasswordUpdatedEvent = array_pop($messages);

        self::assertEquals(new Email($email), $adminPasswordUpdatedEvent->getEmail());
        self::assertEquals(new Name($firstname, $lastname), $adminPasswordUpdatedEvent->getName());
        self::assertEquals(new PlainPassword($password), $adminPasswordUpdatedEvent->getPlainPassword());

        $this->messenger('async')->process();
    }

    public function testUpdateAdminByUnknownAdmin(): void
    {
        $this->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        try {
            $this->dispatch(new UpdateCommand(
                $uuid,
                'new@new.com',
                'New',
                'New',
                Uuid::v4()->__toString(),
                'newpswrd'
            ));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(DomainException::class, $exception->getPrevious());
            self::assertEquals(
                'Admin is not logged in.',
                $exception->getPrevious()->getMessage()
            );
        }
    }
}
