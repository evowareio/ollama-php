<?php

namespace Evoware\OllamaPHP\Traits;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

trait MocksHttpRequests
{
    /**
     * Create a Guzzle Client with a Mock Handler.
     *
     * @param  array  $responses  An array of responses to mock.
     * Each response should be a nested array containing the response code, headers, and body.
     * @return Client A Guzzle Client with a Mock Handler.
     */
    protected function mockHttpClient(array $responses = []): Client
    {
        $mockResponses = [];

        foreach ($responses as $response) {
            $statusCode = $response['code'] ?? $response[0] ?? 200;
            $headers = $response['headers'] ?? $response[1] ?? [];
            $body = $response['body'] ?? $response[2] ?? '';

            if (! is_string($body)) {
                $body = json_encode($body);
            }

            $mockResponses[] = new Response((int) $statusCode, $headers, $body);
        }

        $mock = new MockHandler($mockResponses);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }

    /**
     * Create a Guzzle Client with a Mock Handler for streaming responses.
     *
     * @param  array  $chunks  An array of response chunks to simulate streaming.
     * Each chunk should be a string representing a piece of the streaming response.
     * @return Client A Guzzle Client with a Mock Handler.
     */
    protected function mockStreamingClient(array $chunks): Client
    {
        $responses = [];
        foreach ($chunks as $chunk) {
            $responses[] = new Response(200, ['Content-Type' => 'application/json'], $chunk);
        }

        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);

        return new Client(['handler' => $handlerStack]);
    }
}
