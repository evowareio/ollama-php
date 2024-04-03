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
     * @param array $responses An array of responses to mock. Each response should be a nested array
     *                         containing the response code, headers, and body.
     * @return Client
     */
    protected function mockHttpClient(array $responses): Client
    {
        // Iterate over each response in the input array.
        foreach ($responses as $response) {
            // Extract the response code, headers, and body from the nested array.
            $statusCode = $response['code'] ?? $response[0] ?? 200;
            $headers = $response['headers'] ?? $response[1] ?? [];
            $body = $response['body'] ?? $response[2] ?? '';

            // Create a new instance of the Response class with the extracted values.
            if (!is_string($body)) {
                $body = json_encode($body);
            };

            $mockResponse = new Response((int) $statusCode, $headers, $body);

            // Add the mock response to the array of mocked responses.
            $mockResponses[] = $mockResponse;
        }

        // Create a MockHandler using the array of mocked responses.
        $mock = new MockHandler($mockResponses);

        // Create a handler stack that uses the mock handler.
        $handlerStack = HandlerStack::create($mock);

        // Create and return a new instance of a Guzzle Client with the handler stack.
        return new Client(['handler' => $handlerStack]);
    }
}
