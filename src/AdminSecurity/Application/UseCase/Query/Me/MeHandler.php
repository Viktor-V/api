<?php

declare(strict_types=1);

namespace App\AdminSecurity\Application\UseCase\Query\Me;

use App\Admin\Domain\Entity\Embedded\Email;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Query\QueryHandlerInterface;
use Symfony\Component\Security\Core\Security;
use DomainException;

final class MeHandler implements QueryHandlerInterface
{
    public function __construct(
        private Security $security,
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function __invoke(MeQuery $query): ?Admin
    {
        $user = $this->security->getUser();
        if ($user === null) {
            throw new DomainException('Admin unauthorized.');
        }

        $admin = $this->adminQuery->findByEmail(new Email($user->getUserIdentifier()));
        if ($admin === null) {
            throw new DomainException('Admin not found.');
        }

        return $admin;
    }
}
