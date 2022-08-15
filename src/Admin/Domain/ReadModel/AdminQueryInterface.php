<?php

declare(strict_types=1);

namespace App\Admin\Domain\ReadModel;

use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Common\Domain\Entity\Embedded\Uuid;

interface AdminQueryInterface
{
    public function find(Uuid $uuid): ?Admin;
    public function findByEmail(Email $email): ?Admin;
}
