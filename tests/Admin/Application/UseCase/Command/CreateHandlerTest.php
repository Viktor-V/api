<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class CreateHandlerTest extends ApplicationTestCase
{
    public function testDispatch(): void
    {
        $this->dispatch(new CreateCommand(Uuid::v4()->__toString(), 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(AdminCreatedEvent::class);
        $this->messenger('async')->process();
    }
}
