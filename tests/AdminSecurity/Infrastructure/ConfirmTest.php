<?php

declare(strict_types=1);

namespace App\Tests\AdminSecurity\Infrastructure;

use App\Admin\Application\UseCase\Command\Activate\ActivateCommand;
use App\Admin\Application\UseCase\Command\Create\CreateCommand;
use App\Admin\Domain\Entity\Embedded\Status;
use App\Common\Application\Command\CommandBusInterface;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Uid\Uuid;

class ConfirmTest extends WebTestCase
{
    public function testSuccessfullyConfirm(): void
    {
        $client = self::createClient();

        /** @var CommandBusInterface $commandBus */
        $bus = self::getContainer()->get(CommandBusInterface::class);
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = self::getContainer()->get(Connection::class)->createQueryBuilder();

        $bus->dispatch(new CreateCommand(
            $uuid = Uuid::v4()->__toString(),
            'admin@admin.com',
            'Admin',
            'Admin',
            'pswrd'
        ));

        $confirmationToken = $queryBuilder
            ->select('confirmation_token as confirmationToken')
            ->from('admin')
            ->where('uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->executeQuery()
            ->fetchOne();

        $client->request(
            'PATCH',
            '/api/admin/auth/confirm',
            [],
            [],
            ['CONTENT_TYPE' => 'application/merge-patch+json'],
            json_encode(['confirmationToken' => $confirmationToken, 'status' => 'activated'])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals($responseData['uuid'], $uuid);
        self::assertEquals($responseData['status'], Status::ACTIVATED->value);
    }
}
