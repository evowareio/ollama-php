<?php

namespace Evoware\OllamaPHP\Traits;

use GuzzleHttp\ClientInterface;
use Evoware\OllamaPHP\Responses\OllamaResponseInterface;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Exceptions\OllamaException;

trait MakesHttpRequests
{
    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function request(string $method, string $endpoint, array $data = []): OllamaResponseInterface
    {
        try {
            $data['stream'] = false;
            $response = $this->client->request($method, $endpoint, [
                'json' => $data,
            ]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            throw new OllamaException('Request to Ollama API failed', $e->getCode(), $e);
        }

        switch ($endpoint) {
            case 'generate':
                return new CompletionResponse($response);
            case 'chat':
                return new ChatCompletionResponse($response);
            case 'embeddings':
                return new EmbeddingResponse($response);
            default:
                return new OllamaResponse($response);
        }
    }

    public function get($endpoint, $data = []): OllamaResponseInterface
    {
        return $this->request('GET', $endpoint, $data);
    }

    public function post($endpoint, $data = []): OllamaResponseInterface
    {
        return $this->request('POST', $endpoint, $data);
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
