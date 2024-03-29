<?php

use PHPUnit\Framework\TestCase;
use Evoware\OllamaPHP\OllamaClient;
use Evoware\OllamaPHP\Inference;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

class InferenceTest extends TestCase {
    private $client;
    private $inference;

    protected function setUp(): void {
        $response = '{
            "model": "llama2",
            "created_at": "2023-11-09T21:07:55.186497Z",
            "response": "Oh, what a great test this is.",
            "done": true,
            "context": [],
            "total_duration": 4648158584,
            "load_duration": 4071084,
            "prompt_eval_count": 36,
            "prompt_eval_duration": 439038000,
            "eval_count": 180,
            "eval_duration": 4196918000
          }';

        $guzzleHandler = new MockHandler([
            new Response(200, [], $response),
        ]);

        $this->client = $this->createMock(OllamaClient::class);
        $this->inference = new Inference($this->client);
    }

    public function testGenerateCompletion() {
        $expectedResponse = ['response' => 'Oh, what a great test this is.'];
        $this->client->method('request')
                     ->willReturn($expectedResponse);

        $response = $this->inference->generateCompletion("This is a test.", []);
        $this->assertEquals($expectedResponse['response'], $response);
    }

    public function testGenerateChatCompletion() {
        $expectedResponse = ['response' => 'Hello, how can I help you?'];
        $this->client->method('request')
                     ->willReturn($expectedResponse);

        $messages = [['role' => 'user', 'content' => 'Hi']];
        $response = $this->inference->generateChatCompletion($messages, []);
        $this->assertEquals($expectedResponse['response'], $response);
    }

    // Additional tests as required
}
