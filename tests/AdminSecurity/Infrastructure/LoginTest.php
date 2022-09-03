<?php

declare(strict_types=1);

namespace App\Tests\AdminSecurity\Infrastructure;

use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Block\BlockCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Common\Application\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class LoginTest extends WebTestCase
{
    public function testSuccessfullyLogin(): void
    {
        $client = self::createClient();

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);

        $uuid = Uuid::v4()->__toString();

        $bus->dispatch(new CreateCommand($uuid, 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));
        $bus->dispatch(new ActivateCommand($uuid));

        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'admin@admin.com', 'password' => 'pswrd'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData['token']);
    }

    public function testInvalidCredentials(): void
    {
        $client = self::createClient();
        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'admin@admin.com', 'password' => 'pswrd'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(401);
        self::assertEquals($responseData['message'], 'Invalid credentials.');
    }

    public function testNotActivatedAdmin(): void
    {
        $client = self::createClient();

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);

        $uuid = Uuid::v4()->__toString();

        $bus->dispatch(new CreateCommand($uuid, 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));

        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'admin@admin.com', 'password' => 'pswrd'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(401);
        self::assertEquals($responseData['message'], 'Invalid credentials.');
    }

    public function testBlockedAdmin(): void
    {
        $client = self::createClient();

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);

        $uuid = Uuid::v4()->__toString();

        $bus->dispatch(new CreateCommand($uuid, 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));
        $bus->dispatch(new BlockCommand($uuid));

        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'admin@admin.com', 'password' => 'pswrd'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseStatusCodeSame(401);
        self::assertEquals($responseData['message'], 'Invalid credentials.');
    }
}
