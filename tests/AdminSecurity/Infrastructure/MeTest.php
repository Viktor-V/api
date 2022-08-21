<?php

declare(strict_types=1);

namespace App\Tests\AdminSecurity\Infrastructure;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MeTest extends WebTestCase
{
    use AdminLoginTrait;

    public function testSuccess(): void
    {
        $client = $this->createLoggedClient();
        $client->request('GET', '/api/admin/auth/me');

        $responseData = json_decode($client->getResponse()->getContent());

        self::assertResponseIsSuccessful();
        self::assertNotNull($responseData);
    }
}
