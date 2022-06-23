<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Domain\Event\AdminRegisteredEvent;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class RegisterHandlerTest extends ApplicationTestCase
{
    public function testDispatch(): void
    {
        $this->dispatch(new RegisterCommand(
            Uuid::v4()->__toString(),
            'register@admin.com',
            'Firstname',
            'Lastname',
            'qwert'
        ));

        $this->messenger('sync')->queue()->assertNotEmpty();
        $this->messenger('sync')->queue()->assertContains(RegisterCommand::class);
        $this->messenger('sync')->process();

        $this->messenger('async')->queue()->assertNotEmpty();
        $this->messenger('async')->queue()->assertContains(AdminRegisteredEvent::class);
        $this->messenger('async')->process();
    }
}
