<?php

namespace Evoware\OllamaPHP\Tests;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\Exceptions\OllamaServerException;
use Evoware\OllamaPHP\Exceptions\OllamaClientException;

class IntegrationTest extends TestCase
{
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

    public function testServerErrorHandling()
    {
        $this->expectException(ServerException::class);
        $this->expectException(OllamaServerException::class);
        $this->expectExceptionMessage('Internal Server Error');

        $httpClient = $this->mockHttpClient([
            [500, ['Content-Type' => 'application/json'], json_encode(['error' => 'Internal Server Error'])],
        ]);
        $this->ollamaClient = new OllamaClient($httpClient);
        $model = 'mistral:7b';
        $prompt = 'Hello, world!';
        $response = $this->ollamaClient->setModel($model)->generateCompletion($prompt);

        $this->assertInstanceOf(CompletionResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEmpty($response->getResponse());
        $this->assertFalse($response->isSuccessful());
    }

    public function testClientErrorHandling()
    {
        $this->expectException(ClientException::class);
        $this->expectException(OllamaClientException::class);
        $this->expectExceptionMessage('Missing required parameter: model');

        $httpClient = $this->mockHttpClient([
            [420, ['Content-Type' => 'application/json'], json_encode(['error' => 'The model parameter cannot be empty.'])],
        ]);
        $this->ollamaClient = new OllamaClient($httpClient);
        $prompt = 'Hello, world!';
        $response = $this->ollamaClient->generateCompletion($prompt);

        $this->assertInstanceOf(CompletionResponse::class, $response);
        $this->assertEquals(420, $response->getStatusCode());
        $this->assertEmpty($response->getResponse());
        $this->assertFalse($response->isSuccessful());
    }

    public function testRequiredParameterValidation()
    {
        $this->expectException(OllamaClientException::class);
        $httpClient = $this->mockHttpClient([
            [
                400,
                ['Content-Type' => 'application/json'],
                json_encode(['error' => 'Missing prompt parameter.']),
            ],
        ]);
        $ollamaClient = new OllamaClient($httpClient);

        $ollamaClient->generateEmbeddings('', modelName: 'nomic-embed-text', modelOptions: ['stream' => false]);
    }
}
