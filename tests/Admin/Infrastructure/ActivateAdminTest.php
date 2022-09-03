<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Common\Application\Command\CommandBusInterface;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class ActivateAdminTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testActivate(): void
    {
        $client = $this->createLoggedClient(true);

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);
        $bus->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'new@new.com',
            'New',
            'New',
            'pswrd'
        ));

        $client->request(
            'PATCH',
            sprintf('/api/admins/%s/activate', $uuid),
            [],
            [],
            ['CONTENT_TYPE' => 'application/merge-patch+json'],
            json_encode(['status' => 'activated'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);

        self::assertEquals(Status::ACTIVATED->value, $responseData['status']);
    }
}
