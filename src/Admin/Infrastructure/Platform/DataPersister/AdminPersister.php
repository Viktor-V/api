<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteCommand;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateCommand;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
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
        $this->dispatch($data, $this->operationName($context));

        /** @var Admin */
        return $this->adminQuery->find(new Uuid($data->getUuid()));
    }

    public function remove($data, array $context = []): void
    {
        /** @var Admin $data */
        $this->bus->dispatch(new DeleteCommand($data->getUuid()));
    }

    private function dispatch(Admin $admin, string $operationName): void
    {
        match ($operationName) {
            'post' => $this->bus->dispatch(new RegisterCommand(
                $admin->getUuid(),
                $admin->getEmail(),
                $admin->getFirstname(),
                $admin->getLastname(),
                $admin->getPassword()
            )),
            'patch' => $this->bus->dispatch(new UpdateCommand(
                $admin->getUuid(),
                $admin->getEmail(),
                $admin->getFirstname(),
                $admin->getLastname()
            )),
            'patch_activate' => $this->bus->dispatch(new ActivateCommand(
                $admin->getUuid()
            )),
            'patch_block' => $this->bus->dispatch(new BlockCommand(
                $admin->getUuid()
            ))
        };
    }
}
