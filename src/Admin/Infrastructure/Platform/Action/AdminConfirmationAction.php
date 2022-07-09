<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\Action;

use App\Admin\Application\UseCase\Command\Confirm\ConfirmCommand;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminConfirmationAction
{
    public function __construct(
        private AdminQueryInterface $adminQuery,
        private CommandBusInterface $bus
    ) {
    }

    public function __invoke(string $uuid, string $confirmationToken): Admin
    {
        if ($admin = $this->adminQuery->find(new Uuid($uuid))) {
            // TODO: find easy way how moved it to DataPersister
            $this->bus->dispatch(new ConfirmCommand($confirmationToken));

            return $admin;
        }

        throw new NotFoundHttpException('Not Found');
    }
}
