<?php

namespace Evoware\OllamaPHP\Tests;

use PHPUnit\Framework\TestCase;
use Evoware\OllamaPHP\Traits\MocksGuzzleRequests;
use Evoware\OllamaPHP\OllamaClient;

class OllamaClientTest extends TestCase
{
    use MocksGuzzleRequests;

    public function testInstantiation()
    {
        $ollamaClient = new OllamaClient();
        $this->assertInstanceOf(OllamaClient::class, $ollamaClient);
    }

    public function tesHttpClientInjection()
    {
        $httpClient = new \GuzzleHttp\Client();
        $ollamaClient = new OllamaClient($httpClient);
        $this->assertInstanceOf(OllamaClient::class, $ollamaClient);
    }

    public function tesTestHttpClientInjectionWithOptions()
    {
        $httpClient = new \GuzzleHttp\Client();
        $options = [
            'base_uri' => 'http://localhost:11434',
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ];
        $ollamaClient = new OllamaClient($httpClient, $options);

        $this->assertInstanceOf(OllamaClient::class, $ollamaClient);
        $this->assertEquals('http://localhost:11434', $ollamaClient->getHttpClient()->getConfig('base_uri'));
        $this->assertEquals('application/json', $ollamaClient->getHttpClient()->getConfig('headers')['Content-Type']);
        $this->assertEquals('application/json', $ollamaClient->getHttpClient()->getConfig('headers')['Accept']);
    }

    public function testModelAccess()
    {
        $httpClient = new \GuzzleHttp\Client();
        $ollamaClient = new OllamaClient($httpClient);
        $models = $ollamaClient->model();

        $this->assertInstanceOf(\Evoware\OllamaPHP\Repositories\ModelRepository::class, $models);
    }

    public function testGenerateCompletion()
    {
        $httpClient = new \GuzzleHttp\Client();
        $ollamaClient = new OllamaClient($httpClient);
        $prompt = 'Hello, ';
        $completion = $ollamaClient->generateCompletion('mistral', $prompt);

        $this->assertIsString($completion);
    }

    public function testGenerateChatCompletion()
    {
        $httpClient = new \GuzzleHttp\Client();
        $ollamaClient = new OllamaClient($httpClient);
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'What is the weather like today?']
        ];
        $completion = $ollamaClient->generateChatCompletion('mistral', $messages);
        $this->assertIsString($completion);
    }

    public function testGenerateEmbeddings()
    {
        $httpClient = new \GuzzleHttp\Client();
        $ollamaClient = new OllamaClient($httpClient);
        $texts = [
            'The quick brown fox jumps over the lazy dog.',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'
        ];
        $embeddings = $ollamaClient->generateEmbeddings('llama2', $texts);
        $this->assertIsArray($embeddings);
    }
}
