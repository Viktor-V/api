<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Platform\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Application\DataTransfer\Admin;
use App\Common\Application\Command\CommandBusInterface;
use Symfony\Component\Uid\Uuid;

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

    /**
     * @psalm-param Admin $data
     */
    public function persist($data, array $context = [])
    {
        $this->bus->dispatch(new RegisterCommand(
            $uuid = Uuid::v4()->__toString(),
            $data->email,
            $data->firstname,
            $data->lastname,
            $data->password
        ));

        return Admin::initialization($uuid, $data->email, $data->firstname, $data->lastname, $data->password);
    }

    public function remove($data, array $context = []): void
    {
        // TODO: Implement remove() method.
    }
}
