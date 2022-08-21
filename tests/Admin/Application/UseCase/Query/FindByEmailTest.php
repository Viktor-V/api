<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Query;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;

class FindByEmailTest extends ApplicationTestCase
{
    public function testHandle(): void
    {
        $this->dispatch(new CreateCommand(Uuid::v4()->__toString(), 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));
        $admin = $this->handle(new FindByEmailQuery('admin@admin.com'));

        self::assertNotNull($admin);
    }
}
