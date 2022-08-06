<?php

declare(strict_types=1);

namespace App\AdminSecurity\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\AdminSecurity\Application\UseCase\Command\Confirm\ConfirmCommand;
use App\AdminSecurity\Application\UseCase\Query\Find\FindQuery;
use App\AdminSecurity\Domain\DataTransfer\Admin;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Query\QueryBusInterface;
use App\Common\Infrastructure\Platform\OperationTrait;

class AdminPersister implements ContextAwareDataPersisterInterface
{
    use OperationTrait;

    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Admin;
    }

    public function persist($data, array $context = []): object
    {
        /** @var Admin $data */
        $this->commandBus->dispatch(new ConfirmCommand($data->getUuid()));

        /** @var Admin */
        return $this->queryBus->handle(new FindQuery($data->getUuid()));
    }

    public function remove($data, array $context = []): void
    {
        return;
    }
}
