<?php

declare(strict_types=1);

namespace App\Tests\Admin\Application\UseCase\Query;

use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Tests\Common\Application\ApplicationTestCase;

class FindByEmailTest extends ApplicationTestCase
{
    public function testHandle(): void
    {
        $admin = $this->handle(new FindByEmailQuery('admin@admin.com'));

        self::assertNull($admin);
    }
}
