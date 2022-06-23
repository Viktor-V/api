<?php

declare(strict_types=1);

namespace App\Tests\Common\Application;

use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

abstract class ApplicationTestCase extends KernelTestCase
{
    use InteractsWithMessenger;

    private ?CommandBusInterface $commandBus;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = $this->getService(CommandBusInterface::class);
    }

    protected function dispatch(CommandInterface $command)
    {
        return $this->commandBus->dispatch($command);
    }

    protected function getService(string $id)
    {
        return self::getContainer()->get($id);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->commandBus = null;
    }
}
