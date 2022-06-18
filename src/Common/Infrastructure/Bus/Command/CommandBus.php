<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Command;

use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class CommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
    }

    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
