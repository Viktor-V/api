<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateSuperCommand;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class CreateHandlerTest extends ApplicationTestCase
{
    public function testDispatch(): void
    {
        $this->dispatch(new CreateSuperCommand(
            Uuid::v4()->__toString(),
            'create@admin.com',
            'Firstname',
            'Lastname',
            'qwert'
        ));

        $this->messenger('sync')->queue()->assertNotEmpty();
        $this->messenger('sync')->queue()->assertContains(CreateSuperCommand::class);
        $this->messenger('sync')->process();

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(SuperAdminCreatedEvent::class);
        $this->messenger('async')->process();
    }
}
