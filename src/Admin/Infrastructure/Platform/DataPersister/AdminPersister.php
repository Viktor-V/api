<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Confirm\ConfirmCommand;
use App\Admin\Application\UseCase\Command\Delete\DeleteCommand;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Application\UseCase\Command\Update\UpdateCommand;
use App\Admin\Domain\DataTransfer\Admin;
use App\Admin\Domain\ReadModel\AdminQueryInterface;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Domain\Entity\Embedded\Uuid as ValueObjectUuid;
use Symfony\Component\Uid\Uuid;

class AdminPersister implements ContextAwareDataPersisterInterface
{
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
        if (isset($context['collection_operation_name']) && $context['collection_operation_name'] === 'post') {
            $data->setUuid(Uuid::v4()->__toString());

            $this->bus->dispatch(new RegisterCommand(
                $data->getUuid(),
                $data->getEmail(),
                $data->getFirstname(),
                $data->getLastname(),
                $data->getPassword()
            ));
        }

        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'patch') {
            $this->bus->dispatch(new UpdateCommand(
                $data->getUuid(),
                $data->getEmail(),
                $data->getFirstname(),
                $data->getLastname()
            ));
        }

        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'patch_confirmation') {
            $this->bus->dispatch(new ConfirmCommand($data->getConfirmationToken()));
        }

        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'patch_block') {
            $this->bus->dispatch(new BlockCommand($data->getUuid()));
        }

        /** @var Admin */
        return $this->adminQuery->find(new ValueObjectUuid($data->getUuid()));
    }

    public function remove($data, array $context = []): void
    {
        /** @var Admin $data */
        $this->bus->dispatch(new DeleteCommand($data->getUuid()));
    }
}
