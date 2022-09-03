<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\Common\Application\Command\CommandBusInterface;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class ListAdminTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testList(): void
    {
        $client = $this->createLoggedClient();

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);

        $bus->dispatch(new CreateSuperCommand(
            Uuid::v4()->__toString(),
            'super@super.com',
            'Super',
            'Super',
            'pswrd'
        ));
        $bus->dispatch(new CreateCommand(
            Uuid::v4()->__toString(),
            'new@new.com',
            'New',
            'New',
            'pswrd'
        ));

        $client->request('GET', '/api/admins');

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);

        self::assertEquals(3, $responseData['hydra:totalItems']); // +1 logged in admin
    }
}
