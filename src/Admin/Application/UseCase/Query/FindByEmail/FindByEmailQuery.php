<?php

declare(strict_types=1);

namespace App\Admin\Application\UseCase\Query\FindByEmail;

use App\Admin\Domain\Entity\Embedded\Email;
use App\Common\Application\Query\QueryInterface;

class FindByEmailQuery implements QueryInterface
{
    public readonly Email $email;

    public function __construct(
        string $email
    ) {
        $this->email = new Email($email);
    }
}
