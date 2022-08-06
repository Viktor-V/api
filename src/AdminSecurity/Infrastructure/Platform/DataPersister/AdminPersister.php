<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\AdminSecurity\Application\UseCase\Command\Confirm\ConfirmCommand;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use App\Common\Infrastructure\Platform\OperationTrait;

class AdminPersister implements ContextAwareDataPersisterInterface
{
    use OperationTrait;

    public function __construct(
        private CommandBusInterface $bus,
        private AdminQueryInterface $adminQuery
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Admin;
    }

    public function persist($data, array $context = []): object
    {
        /** @var Admin $data */
        $this->bus->dispatch(new ConfirmCommand($data->getUuid()));

        /** @var Admin */
        return $this->adminQuery->find(new Uuid($data->getUuid())); // TODO: handler
    }

    public function remove($data, array $context = []): void
    {
        return;
    }
}
