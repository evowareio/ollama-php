<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use Evoware\OllamaPHP\OllamaClient;

class InferenceTest extends TestCase
{
    private OllamaClient $ollamaClient;
    private Client $guzzleClient;
    private string $responseString;

    protected function setUp(): void
    {
        $this->responseString = 'Oh, what a great test this is.';
        $mockResponse = sprintf('{
            "model": "llama2",
            "created_at": "2023-11-09T21:07:55.186497Z",
            "response": %s,
            "done": true,
            "context": [],
            "total_duration": 4648158584,
            "load_duration": 4071084,
            "prompt_eval_count": 36,
            "prompt_eval_duration": 439038000,
            "eval_count": 180,
            "eval_duration": 4196918000
        }', $this->responseString);

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponse)
        ]);
        // Create a Guzzle client with the mocked handler
        $this->guzzleClient = new Client(['handler' => HandlerStack::create($mockHandler)]);

        // Initialize OllamaClient with the mocked Guzzle client
        $this->ollamaClient = new OllamaClient($this->guzzleClient);
    }

    public function testInference()
    {
        $this->markTestIncomplete('This test has not been implemented yet.');
        $ollamaResponse = $this->ollamaClient->generateCompletion('llama2', 'Oh, what a great test this is.');

        $this->assertEquals($this->responseString, $ollamaResponse->getHttpResponse()->getBody());
        $this->assertEquals('Oh, what a great test this is.', $ollamaResponse->getResponse());
    }
}
