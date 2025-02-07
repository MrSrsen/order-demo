<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Tests\FixturesTrait;

class OrderTest extends ApiTestCase
{
    use FixturesTrait;

    private Client $client;

    public static function setUpBeforeClass(): void
    {
        $container = self::getContainer();
        self::executeFixtures($container, [
            'order.yaml',
        ]);
    }

    protected function setUp(): void
    {
        $this->client = $this->createClient();
    }

    public function testGettingOrder(): void
    {
        $this->client->request('GET', '/api/orders/1');
        $this->assertResponseIsSuccessful();

        $this->assertJsonContains([
            'number' => '001',
            'createdAt' => '2025-02-07T00:00:00+01:00',
            'title' => 'First order',
            'total' => '970.00',
            'currency' => 'CZK',
            'state' => 'new',
            'orderItems' => [
                [
                    'title' => 'Shirt',
                    'unitPrice' => '100.00',
                    'quantity' => '1.00',
                    'total' => '100.00',
                ],
                [
                    'title' => 'Shoes',
                    'unitPrice' => '870.00',
                    'quantity' => '1.00',
                    'total' => '870.00',
                ],
            ],
        ]);
    }

    public function testGettingXmlOrder(): void
    {
        $response = $this->client->request('GET', '/api/orders/1', [
            'headers' => [
                'Accept' => 'application/xml',
            ],
        ]);
        $this->assertResponseIsSuccessful();

        $this->assertEquals(\file_get_contents(__DIR__.'/order_response.xml'), $response->getContent(false));
    }

    public function testGettingCsvOrder(): void
    {
        $response = $this->client->request('GET', '/api/orders/1', [
            'headers' => [
                'Accept' => 'text/csv',
            ],
        ]);
        $this->assertResponseIsSuccessful();

        $this->assertEquals(\file_get_contents(__DIR__.'/order_response.csv'), $response->getContent(false));
    }
}
