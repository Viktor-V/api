<?php

declare(strict_types=1);

namespace App\Admin\Domain\Specification;

use App\Admin\Domain\ValueObject\Email;
use App\Common\Domain\Specification\SpecificationInterface;

interface UniqueEmailSpecificationInterface extends SpecificationInterface
{
    public function injectEmail(Email $email): self;
}
