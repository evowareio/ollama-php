<?php

namespace Evoware\OllamaPHP\Tests;

use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Traits\MocksHttpRequests;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    use MocksHttpRequests;

    private $httpClient;

    private $ollamaClient;

    public function testGenerateCompletionSuccess()
    {
        $this->httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], json_encode(['response' => 'Generated completion goes here.', 'done' => true])],
        ]);
        $this->ollamaClient = new OllamaClient($this->httpClient);

        $model = 'mistral:7b';
        $prompt = 'Hello, world!';

        $response = $this->ollamaClient->useModel($model)->generateCompletion($prompt);

        $this->assertInstanceOf(CompletionResponse::class, $response);

        $this->assertEquals(200, $response->getHttpResponse()->getStatusCode());

        $this->assertNotEmpty($response->getResponse());
        $this->assertEquals('Generated completion goes here.', $response->getResponse());
        $this->assertTrue($response->isDone());
    }
}
