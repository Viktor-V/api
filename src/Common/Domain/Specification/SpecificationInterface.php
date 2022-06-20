<?php

declare(strict_types=1);

namespace App\Common\Domain\Specification;

interface SpecificationInterface
{
    /**
     * @throws SpecificationException
     */
    public function isSatisfied(mixed ...$values): bool;
}
