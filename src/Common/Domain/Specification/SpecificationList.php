<?php

declare(strict_types=1);

namespace App\Common\Domain\Specification;

final class SpecificationList implements SpecificationInterface
{
    /**
     * @param SpecificationInterface[] $specifications
     */
    public function __construct(
        private array $specifications
    ) {
    }

    /**
     * @inheritdoc
     */
    public function isSatisfied(mixed ...$values): bool
    {
        foreach ($this->specifications as $specification) {
            $specification->isSatisfied(...$values);
        }

        return true;
    }
}
