<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Confirm\ConfirmCommand;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
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
        if (isset($context['item_operation_name']) && $context['item_operation_name'] === 'patch_confirmation') {
            $this->bus->dispatch(new ConfirmCommand($data->getConfirmationToken()));
        }

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

        /** @var Admin */
        return $this->adminQuery->find(new ValueObjectUuid($data->getUuid()));
    }

    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
