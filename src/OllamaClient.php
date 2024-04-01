<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\Client as HttpClient;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Exceptions\OllamaException;

class OllamaClient
{
    private HttpClient $httpClient;

    public function __construct($baseUri)
    {
        $this->httpClient = new HttpClient(['base_uri' => $baseUri, 'headers' => ['Content-Type' => 'application/json']]);
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
