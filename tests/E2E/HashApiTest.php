<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HashApiTest extends WebTestCase
{
    /**
     * @dataProvider hashProvider()
     */
    public function testCriaHash($entrada)
    {
        $client = static::createClient();

        $crawler = $client->request('POST', "/api/hash/$entrada");

        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $responseData = json_decode($response->getContent());

        $this->assertMatchesRegularExpression('/^0000/', $responseData->hash);
        $this->assertNotEmpty($responseData->hash);
        $this->assertNotEmpty($responseData->key);
        $this->assertGreaterThan(0, $responseData->tentativas);
    }

    public function hashProvider()
    {
        yield ['Ávato'];
        yield ['0000e5a78737e88a6065b37aa698bb4d'];
        yield ['0000a93f9f287a03912c8a61d79ffb23'];
        yield ['0000e4c93fbdc09d46361dafc0b5a00c'];
        yield ['00003794acd6639aa65e1be105a568e6'];
        yield ['0000e350aea8329b93a7346e77cb47b3'];
        yield ['0000f06cc0271af767f1976526a0a4ec'];
    }

    public function testLimita10RequisicoesPorIp(): void
    {
        $this->markTestIncomplete('Implementar');
    }
}
