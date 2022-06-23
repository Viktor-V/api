<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Command;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Tests\Common\Application\ApplicationTestCase;

class CreateHandlerTest extends ApplicationTestCase
{
    public function testDispatch(): void
    {
        $this->dispatch(new CreateCommand(
            'b48b643e-a9b8-41a6-802d-0b438b566f62',
            'admin@admin.com',
            'Firstname',
            'Lastname',
            'qwert'
        ));

        $this->messenger('sync')->queue()->assertNotEmpty();
        $this->messenger('sync')->queue()->assertContains(CreateCommand::class);

        $this->messenger('async')->queue()->assertEmpty();
    }
}
