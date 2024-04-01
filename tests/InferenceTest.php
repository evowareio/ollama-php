<?php

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Evoware\OllamaPHP\OllamaClient;

class InferenceTest extends TestCase
{
    private OllamaClient $client;

    protected function setUp(): void
    {
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
    }
}
