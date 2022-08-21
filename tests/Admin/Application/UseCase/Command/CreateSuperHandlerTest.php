<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\Admin\Domain\Event\SuperAdminCreatedEvent;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Messenger\Transport\InMemoryTransport;
use Symfony\Component\Uid\Uuid;

class CreateSuperHandlerTest extends ApplicationTestCase
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

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(SuperAdminCreatedEvent::class);
        $this->messenger('async')->process();
    }
}
