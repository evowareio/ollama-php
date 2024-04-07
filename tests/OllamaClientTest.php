<?php

namespace Evoware\OllamaPHP\Tests;

use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Traits\MocksHttpRequests;

class OllamaClientTest extends TestCase
{
    use MocksHttpRequests;

    public function tesHttpClientInjection()
    {
        $httpClient = $this->mockHttpClient();
        $ollamaClient = new OllamaClient($httpClient);

        $this->assertInstanceOf(OllamaClient::class, $ollamaClient);
        $this->assertEquals($httpClient, $ollamaClient->getHttpClient());
    }

    public function testHttpClientInjectionWithOptions()
    {
        $httpClient = $this->mockHttpClient();
        $options = [
            'base_uri' => 'http://localhost:11434',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];
        $ollamaClient = new OllamaClient($httpClient, $options);

        $this->assertInstanceOf(OllamaClient::class, $ollamaClient);
        $this->assertEquals($httpClient, $ollamaClient->getHttpClient());
    }

    public function testModelChainAccess()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], ['status' => 'success']],
        ]);
        $ollamaClient = new OllamaClient($httpClient);

        $this->assertInstanceOf(ModelRepository::class, $ollamaClient->model());

        $result = $ollamaClient->model()->pull('llama2');

        $this->assertTrue($result);
    }

    public function testGenerateCompletion()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], '{"response":"Generated response$response goes here.","done":true}'],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $prompt = 'Hello, ';
        $response = $ollamaClient->generateCompletion($prompt, 'mistral');

        $this->assertIsString($response->getResponse());
    }

    public function testGenerateCompletionWithOptions()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], '{"response":"Generated response$response goes here.","done":true}'],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $prompt = 'Hello, ';
        $response = $ollamaClient->generateCompletion($prompt, modelOptions: [
            'max_tokens' => 50,
            'temperature' => 0.5,
        ]);

        $this->assertInstanceOf(CompletionResponse::class, $response);
        $this->assertNotEmpty($response->getResponse());
        $this->assertStringStartsWith('Generated response$response ', $response->getResponse());
    }

    public function testGenerateChatCompletion()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], ['message' => ['role' => 'user', 'content' => 'Chat response test here!'], 'done' => true]],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $messages = [
            ['role' => 'user', 'content' => 'What is the weather like today?'],
        ];
        $response = $ollamaClient->generateChatCompletion($messages, 'mistral');

        $this->assertIsArray($response->getMessage());
        $this->assertEquals(['role' => 'user', 'content' => 'Chat response test here!'], $response->getMessage());
    }

    public function testGenerateChatCompletionWithOptions()
    {
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], ['message' => ['role' => 'user', 'content' => 'Chat response test here!'], 'done' => true]],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $messages = [
            ['role' => 'user', 'content' => 'What is the weather like today?'],
        ];

        $response = $ollamaClient->generateChatCompletion($messages, modelOptions: [
            'max_tokens' => 50,
            'temperature' => 0.5,
            'model' => 'mistral',
        ]);
        $this->assertInstanceOf(ChatCompletionResponse::class, $response);
        $this->assertNotEmpty($response->getMessage());
        $this->assertIsArray($response->getMessage());
    }

    public function testGenerateEmbeddings()
    {
        $testEmbedding = [
            0.5670403838157654,
            0.23178744316101074,
            -0.2916173040866852,
            -0.8924556970596313,
            0.8785552978515625,
            -0.34576427936553955,
            0.5742510557174683,
            -0.04222835972905159,
            -0.137906014919281,
        ];
        $httpClient = $this->mockHttpClient([
            [200, ['Content-Type' => 'application/json'], ['embedding' => $testEmbedding]],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $inputText = 'Test';

        $response = $ollamaClient->generateEmbeddings($inputText, 'nomic-embed-text');

        $this->assertEquals($testEmbedding, $response->getEmbedding());
    }

    public function testGenerateEmbeddingsWithOptions()
    {
        $httpClient = $this->mockHttpClient([
            [
                200,
                ['Content-Type' => 'application/json'],
                json_encode(['embedding' => [
                    0.5670403838157654, 0.009260174818336964, 0.23178744316101074, -0.2916173040866852, -0.8924556970596313,
                    0.8785552978515625, -0.34576427936553955, 0.5742510557174683, -0.04222835972905159, -0.137906014919281,
                ]]),
            ],
        ]);
        $ollamaClient = new OllamaClient($httpClient);
        $prompt = 'The quick brown fox jumps over the lazy dog.';

        $response = $ollamaClient->generateEmbeddings($prompt, modelName: 'nomic-embed-text', modelOptions: ['stream' => false]);

        $this->assertInstanceOf(EmbeddingResponse::class, $response);
        $this->assertIsArray($response->getEmbedding());
        $this->assertEquals(0.5670403838157654, $response->getEmbedding()[0]);
    }
}
