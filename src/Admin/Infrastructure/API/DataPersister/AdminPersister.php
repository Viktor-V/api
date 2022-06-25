<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\API\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Admin\Application\UseCase\Command\Register\RegisterCommand;
use App\Admin\Infrastructure\API\Dto\AdminDto;
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
        return $data instanceof AdminDto;
    }

    /**
     * @param AdminDto $data
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

        $data->injectUuid($uuid);

        return $data;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}
