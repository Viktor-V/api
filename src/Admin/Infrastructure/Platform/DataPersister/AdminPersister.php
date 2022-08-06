<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Admin\Domain\DataTransfer\Admin;
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
        $this->dispatch($data, $this->operationName($context));

        /** @var Admin */
        return $this->queryBus->handle(new FindQuery($data->getUuid()));
    }

    public function remove($data, array $context = []): void
    {
        /** @var Admin $data */
        $this->commandBus->dispatch(new DeleteCommand($data->getUuid()));
    }

    private function dispatch(Admin $admin, string $operationName): void
    {
        match ($operationName) {
            'post' => $this->commandBus->dispatch(new CreateCommand(
                $admin->getUuid(),
                $admin->getEmail(),
                $admin->getFirstname(),
                $admin->getLastname(),
                $admin->getPassword()
            )),
            'patch' => $this->commandBus->dispatch(new UpdateCommand(
                $admin->getUuid(),
                $admin->getEmail(),
                $admin->getFirstname(),
                $admin->getLastname()
            )),
            'patch_activate' => $this->commandBus->dispatch(new ActivateCommand(
                $admin->getUuid()
            )),
            'patch_block' => $this->commandBus->dispatch(new BlockCommand(
                $admin->getUuid()
            ))
        };
    }
}
