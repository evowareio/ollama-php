<?php

namespace Evoware\OllamaPHP\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client as HttpClient;
use Evoware\OllamaPHP\Traits\MocksGuzzleRequests;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\OllamaClient;

class IntegrationTest extends TestCase
{
    use MocksGuzzleRequests;

    private $httpClient;
    private $ollamaClient;

    public function testGenerateCompletionSuccess()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');

        // Mock Http Client using the MockGuzzleRequests trait
        $this->httpClient = $this->createMockedGuzzleClient([
            ['code' => 200, 'body' => 'Hello, world!']
        ])

        $this->httpClient = $this->createMock(HttpClient::class);
        $this->ollamaClient = new OllamaClient($this->httpClient);
        
        $model = 'mistral:7b';
        $prompt = 'Hello, world!';

        $response = $this->ollamaClient->generateCompletion($model, $prompt);

        $this->assertInstanceOf(OllamaResponse::class, $response);

        $this->assertEquals(200, $response->getStatusCode());

        $this->assertNotEmpty($response->getBody());
        $this->assertIsArray($response->getBody());
    }
}
