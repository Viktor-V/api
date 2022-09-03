<?php

declare(strict_types=1);

namespace App\Tests\Common\Infrastructure;

use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\Common\Application\Command\CommandBusInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Uid\Uuid;

trait AdminLoginTrait
{
    public function createLoggedClient(bool $super = false): KernelBrowser
    {
        /** @var KernelBrowser $client */
        $client = self::createClient();

        /** @var CommandBusInterface $bus */
        $bus = self::getContainer()->get(CommandBusInterface::class);

        $uuid = Uuid::v4()->__toString();

        if ($super === true) {
            $bus->dispatch(new CreateSuperCommand($uuid, 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));
        } else {
            $bus->dispatch(new CreateCommand($uuid, 'admin@admin.com', 'Admin', 'Admin', 'pswrd'));
            $bus->dispatch(new ActivateCommand($uuid));
        }

        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => 'admin@admin.com', 'password' => 'pswrd'])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }
}
