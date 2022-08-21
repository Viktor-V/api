<?php

declare(strict_types=1);

namespace App\Tests\AdminSecurity\Infrastructure;

use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Application\UseCase\Command\CreateSuper\CreateSuperCommand;
use App\AdminSecurity\Domain\ReadModel\AdminQueryInterface;
use App\AdminSecurity\Domain\DataTransfer\Admin as SecuredAdmin;
use App\Common\Application\Command\CommandBusInterface;
use App\Common\Domain\Entity\Embedded\Uuid;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

trait AdminLoginTrait
{
    public function createLoggedClient(bool $super = false): KernelBrowser
    {
        /** @var KernelBrowser $client */
        $client = static::createClient();

        $admin = $this->createAdmin($super);

        $client->request(
            'POST',
            '/api/admin/auth/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['email' => $admin->getEmail(), 'password' => 'pswrd'])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    private function createAdmin(bool $super): SecuredAdmin
    {
        /** @var CommandBusInterface $bus */
        $bus = static::getContainer()->get(CommandBusInterface::class);

        $uuid = \Symfony\Component\Uid\Uuid::v4()->__toString();

        if ($super === true) {
            $bus->dispatch(new CreateSuperCommand($uuid, 'admin@admin.com', 'admin', 'admin', 'pswrd'));
        } else {
            $bus->dispatch(new CreateCommand($uuid, 'admin@admin.com', 'admin', 'admin', 'pswrd'));
            $bus->dispatch(new ActivateCommand($uuid));
        }

        /** @var AdminQueryInterface $adminQuery */
        $adminQuery = static::getContainer()->get(AdminQueryInterface::class);

        return $adminQuery->find(new Uuid($uuid));
    }
}
