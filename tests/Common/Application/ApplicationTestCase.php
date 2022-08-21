<?php

declare(strict_types=1);

namespace App\Tests\Common\Application;

use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Command\CommandInterface;
use App\Common\Application\Query\QueryBusInterface;
use App\Common\Application\Query\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

abstract class ApplicationTestCase extends KernelTestCase
{
    use InteractsWithMessenger;

    private ?CommandBusInterface $commandBus;
    private ?QueryBusInterface $queryBus;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = $this->getService(CommandBusInterface::class);
        $this->queryBus = $this->getService(QueryBusInterface::class);
    }

    protected function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }

    protected function handle(QueryInterface $query): mixed
    {
        return $this->queryBus->handle($query);
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
