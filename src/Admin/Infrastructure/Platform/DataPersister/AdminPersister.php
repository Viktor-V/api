<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Domain\DataTransfer\Admin;
use App\Common\Application\Command\CommandBusInterface;

class AdminPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private CommandBusInterface $bus
    ) {
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Admin;
    }

    public function persist($data, array $context = []): object
    {
        /** @var Admin $data */
        $this->bus->dispatch(new RegisterCommand(
            $data->uuid,
            $data->email,
            $data->firstname,
            $data->lastname,
            $data->password
        ));

        return $data;
    }

    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
