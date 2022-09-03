<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Common\Application\Command\CommandBusInterface;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class FindAdminTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testFind(): void
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

        $client->request('GET', sprintf('/api/admins/%s', $uuid));

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals('new@new.com', $responseData['email']);
    }
}
