<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Specification;

use App\Admin\Domain\Repository\AdminRepositoryInterface;
use App\Admin\Domain\Specification\UniqueEmailSpecificationInterface;
use App\Admin\Domain\Entity\Embedded\Email;
use App\Common\Domain\Specification\SpecificationException;

class UniqueEmailSpecification implements UniqueEmailSpecificationInterface
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function isSatisfied(mixed ...$values): bool
    {
        /** @var Email $email */
        [$email] = $values;

        if ($this->adminRepository->findByEmail($email)) {
            throw new SpecificationException(
                sprintf('Admin already exists with such email %s.', $email->__toString())
            );
        }

        return true;
    }
}
