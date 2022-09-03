<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\Find\FindQuery;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Query\QueryBusInterface;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class DeleteAdminTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testDelete(): void
    {
        $client = $this->createLoggedClient(true);

        /** @var CommandBusInterface $commandBus */
        $commandBus = self::getContainer()->get(CommandBusInterface::class);
        $commandBus->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'new@new.com',
            'New',
            'New',
            'pswrd'
        ));

        $client->request('DELETE', sprintf('/api/admins/%s', $uuid));

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNull($responseData);

        /** @var QueryBusInterface $queryBus */
        $queryBus = self::getContainer()->get(QueryBusInterface::class);

        self::assertNull($queryBus->handle(new FindQuery($uuid)));
    }
}
