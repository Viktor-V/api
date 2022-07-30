<?php

declare(strict_types=1);

namespace App\Admin\Infrastructure\Command;

use App\Admin\Application\UseCase\Command\Create\CreateSuperCommand;
use App\Common\Application\Command\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Throwable;

#[AsCommand(
    name: self::COMMAND_NAME,
    description: self::COMMAND_DESCRIPTION
)]
class AdminCreateCommand extends Command
{
    private const COMMAND_NAME = 'app:admin:create';
    private const COMMAND_DESCRIPTION = 'Create admin';

    public function __construct(
        private CommandBusInterface $bus,
        string $name = self::COMMAND_NAME
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $email = (string) $io->ask('Please enter admin email');
            $firstname = (string) $io->ask('Please enter admin firstname');
            $lastname = (string) $io->ask('Please enter admin lastname');
            $password = (string) $io->askHidden('Please enter admin password');

            $this->bus->dispatch(new CreateSuperCommand(
                Uuid::v4()->__toString(),
                $email,
                $firstname,
                $lastname,
                $password
            ));
        } catch (Throwable $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success('Admin successfully created.');

        return Command::SUCCESS;
    }
}
