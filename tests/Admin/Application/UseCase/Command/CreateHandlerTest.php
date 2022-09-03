<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Specification\SpecificationException;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;

class CreateHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulAdminCreated(): void
    {
        $this->dispatch(new CreateCommand(
            Uuid::v4()->__toString(),
            $email = 'admin@admin.com',
            $firstname = 'Admin',
            $lastname = 'Admin',
            $password = 'pswrd'
        ));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(AdminCreatedEvent::class);

        $messages = $this->messenger('async')->queue()->messages(AdminCreatedEvent::class);
        /** @var AdminCreatedEvent $adminCreatedEvent */
        $adminCreatedEvent = array_pop($messages);

        self::assertEquals(new Email($email), $adminCreatedEvent->getEmail());
        self::assertEquals(new Name($firstname, $lastname), $adminCreatedEvent->getName());
        self::assertEquals(new PlainPassword($password), $adminCreatedEvent->getPlainPassword());
        self::assertNotNull($adminCreatedEvent->getConfirmationToken()->__toString());

        $this->messenger('async')->process();
    }

    public function testSuperAdminAlreadyExists(): void
    {
        $email = 'admin@admin.com';

        $this->dispatch(new CreateCommand(
            Uuid::v4()->__toString(),
            $email,
            'Admin',
            'Admin',
            'pswrd'
        ));

        try {
            $this->dispatch(new CreateCommand(
                Uuid::v4()->__toString(),
                $email,
                'Admin',
                'Admin',
                'pswrd'
            ));
        } catch (HandlerFailedException $exception) {
            self::assertInstanceOf(SpecificationException::class, $exception->getPrevious());
            self::assertEquals(
                "Admin already exists with such email ${email}.",
                $exception->getPrevious()->getMessage()
            );
        }
    }
}
