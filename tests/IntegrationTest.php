<?php

namespace Evoware\OllamaPHP\Tests;

use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Traits\MocksHttpRequests;

class IntegrationTest extends TestCase
{
    use MocksHttpRequests;

    private $ollamaClient;

    public function testGenerateCompletionSuccess()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], json_encode([
                'model' => 'mistral:7b',
                'created_at' => date('Y-m-d H:i:s'),
                'response' => 'Generated completion goes here.',
                'done' => true,
            ])],
        ]);
        $this->ollamaClient = new OllamaClient($httpClient);

        $model = 'mistral:7b';
        $prompt = 'Hello, world!';

        $response = $this->ollamaClient->setModel($model)->generateCompletion($prompt);

        $this->assertInstanceOf(CompletionResponse::class, $response);

        $this->assertEquals(200, $response->getHttpResponse()->getStatusCode());

        $this->assertNotEmpty($response->getResponse());
        $this->assertEquals('Generated completion goes here.', $response->getResponse());
        $this->assertTrue($response->isDone());
    }

    public function testGenerateCompletionFailure()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Internal Server Error');

        $httpClient = $this->mockHttpClient([
            [500, ['Content-Type' => 'application/json'], json_encode(['error' => 'Internal Server Error'])],
        ]);
        $this->ollamaClient = new OllamaClient($httpClient);

        $model = 'mistral:7b';
        $prompt = 'Hello, world!';

        $response = $this->ollamaClient->setModel($model)->generateCompletion($prompt);

        $this->assertInstanceOf(CompletionResponse::class, $response);

        $this->assertEquals(500, $response->getHttpResponse()->getStatusCode());

        $this->assertEmpty($response->getResponse());
        $this->assertFalse($response->isDone());
    }
}
