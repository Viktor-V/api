<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure;

use App\Admin\Domain\Entity\Embedded\Status;
use App\Tests\Common\Infrastructure\AdminLoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateAdminTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testCreate(): void
    {
        $client = $this->createLoggedClient(true);
        $client->request(
            'POST',
            '/api/admins',
            [],
            [],
            ['CONTENT_TYPE' => 'application/ld+json'],
            json_encode([
                'email' => 'new@new.com',
                'firstname' => 'New',
                'lastname' => 'New',
                'password' => 'new_pswdr_123'
            ])
        );

        $responseData = json_decode($client->getResponse()->getContent(), true);

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
        self::assertEquals(Status::DISABLED->value, $responseData['status']);
    }
}
