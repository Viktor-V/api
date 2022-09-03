<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Query\FindByEmail\FindByEmailQuery;
use App\Admin\Domain\DataTransfer\Admin;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Application\Query\QueryBusInterface;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class UpdateAdminTest extends WebTestCase
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

        $client->request(
            'PATCH',
            sprintf('/api/admins/%s', $uuid),
            [],
            [],
            ['CONTENT_TYPE' => 'application/merge-patch+json'],
            json_encode(['email' => 'old@old.com', 'firstname' => 'Old', 'lastname' => 'Old'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals('old@old.com', $responseData['email']);
        self::assertEquals('Old', $responseData['firstname']);
        self::assertEquals('Old', $responseData['lastname']);
    }

    public function testChangePasswordBySuperAdmin(): void
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

        $client->request(
            'PATCH',
            sprintf('/api/admins/%s', $uuid),
            [],
            [],
            ['CONTENT_TYPE' => 'application/merge-patch+json'],
            json_encode(['password' => 'new_pswdr_123'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
    }

    public function testChangePasswordBySelf(): void
    {
        $client = $this->createLoggedClient();

        /** @var QueryBusInterface $queryBus */
        $queryBus = self::getContainer()->get(QueryBusInterface::class);
        /** @var Admin $admin */
        $admin = $queryBus->handle(new FindByEmailQuery('admin@admin.com'));

        $client->request(
            'PATCH',
            sprintf('/api/admins/%s', $admin->getUuid()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/merge-patch+json'],
            json_encode(['password' => 'new_pswdr_123', 'confirmPassword' => 'new_pswdr_123', 'oldPassword' => 'pswrd'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
    }
}
