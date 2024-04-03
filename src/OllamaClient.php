<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\Client as HttpClient;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Exceptions\OllamaException;

class OllamaClient
{
    private HttpClient $httpClient;

    public function __construct($baseUri)
    {
        $this->httpClient = new HttpClient([
            'base_uri' => $baseUri,
            'headers' => ['Content-Type' => 'application/json']
        ]);
    }

    public function models(): ModelRepository
    {
        return new ModelRepository($this->httpClient);
    }

    public function generateCompletion(string $model, string $prompt, array $options = []): OllamaResponse
    {
        $response = $this->httpClient->post('completion', [
            'json' => [
                'model' => $model,
                'prompt' => $prompt,
                'options' => $options
            ]
        ]);

        return new OllamaResponse($response);
    }

    public function generateChatCompletion(string $model, array $messages, array $options = []): OllamaResponse
    {
        $response = $this->httpClient->post('chat_completion', [
            'json' => [
                'model' => $model,
                'messages' => $messages,
                'options' => $options
            ]
        ]);

        return new OllamaResponse($response);
    }

    public function generateEmbeddings(string $model, $prompt, array $options = []): OllamaResponse
    {
        $response = $this->httpClient->post('embeddings', [
            'json' => [
                'model' => $model,
                'prompt' => $prompt,
                'options' => $options
            ]
        ]);

        return new OllamaResponse($response);
    }
}
