<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Admin\Domain\Entity\Embedded\Name;
use App\Admin\Domain\Entity\Embedded\PlainPassword;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Common\Domain\Specification\SpecificationException;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Uid\Uuid;

class CreateSuperHandlerTest extends ApplicationTestCase
{
    public function testSuccessfulSuperAdminCreated(): void
    {
        $this->dispatch(new CreateSuperCommand(
            Uuid::v4()->__toString(),
            $email = 'super@super.com',
            $firstname = 'Super',
            $lastname = 'Super',
            $password = 'pswrd'
        ));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(SuperAdminCreatedEvent::class);

        $messages = $this->messenger('async')->queue()->messages(SuperAdminCreatedEvent::class);
        /** @var SuperAdminCreatedEvent $superAdminCreatedEvent */
        $superAdminCreatedEvent = array_pop($messages);

        self::assertEquals(new Email($email), $superAdminCreatedEvent->getEmail());
        self::assertEquals(new Name($firstname, $lastname), $superAdminCreatedEvent->getName());
        self::assertEquals(new PlainPassword($password), $superAdminCreatedEvent->getPlainPassword());

        $this->messenger('async')->process();
    }

    public function testSuperAdminAlreadyExists(): void
    {
        $email = 'super@super.com';

        $this->dispatch(new CreateSuperCommand(
            Uuid::v4()->__toString(),
            $email,
            'Super',
            'Super',
            'pswrd'
        ));

        try {
            $this->dispatch(new CreateSuperCommand(
                Uuid::v4()->__toString(),
                $email,
                'super',
                'Super',
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
