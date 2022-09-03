<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Query;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\Admin\Application\UseCase\Query\List\ListQuery;
use App\Tests\Common\Application\ApplicationTestCase;
use Symfony\Component\Uid\Uuid;
use Generator;

class ListTest extends ApplicationTestCase
{
    public function testList(): void
    {
        $this->dispatch(new CreateSuperCommand(
            Uuid::v4()->__toString(),
            'super@super.com',
            'Super',
            'Super',
            'pswrd'
        ));
        $this->dispatch(new CreateCommand(
            Uuid::v4()->__toString(),
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));
        /** @var Generator $list */
        $list = $this->handle(new ListQuery(1, 20));

        self::assertCount(2, $list);
    }
}
