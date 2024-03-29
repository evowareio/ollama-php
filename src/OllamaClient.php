<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\Client;
use Evoware\OllamaPHP\Exceptions\OllamaException;

class OllamaClient {
    private $client;

    public function __construct($baseUri) {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    public function request($method, $uri, array $options = []) {
        try {
            $response = $this->client->request($method, $uri, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $e) {
            throw new OllamaException($e->getMessage(), $e->getCode());
        }
    }

    public function generateCompletion($model, $prompt, $images = null, $options = []) {
        $data = array_merge(['model' => $model, 'prompt' => $prompt, 'images' => $images], $options);
        
        return $this->client->post('generate', $data);
    }

    public function generateChatCompletion($model, $prompt, $images = null, $options = []) {
        $data = array_merge(['model' => $model, 'prompt' => $prompt, 'images' => $images], $options);
        return $this->client->post('generate/chat', $data);
    }

    public function generateEmbeddings($modelName, $prompt) {
        return $this->client->post("models/$modelName/embeddings", ['prompt' => $prompt]);
    }
}
