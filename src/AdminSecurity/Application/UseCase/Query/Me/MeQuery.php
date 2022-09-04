<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\Me;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Common\Application\Query\QueryInterface;

final class MeQuery implements QueryInterface
{
    public readonly Email $email;

    public function __construct(
        string $email
    ) {
        $this->email = new Email($email);
    }
}
