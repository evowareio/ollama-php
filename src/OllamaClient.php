<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client as HttpClient;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Repositories\ModelRepository;

class OllamaClient
{
    private ClientInterface $httpClient;
    private string $modelName = '';
    private array $modelOptions = [];

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getModelRepository(): ModelRepository
    {
        return new ModelRepository($this->httpClient);
    }

    public function generateCompletion(string $prompt, array $modelOptions = []): CompletionResponse
    {
        if (empty($this->modelName)) {
            throw new \InvalidArgumentException('No model is set. Use useModel() to set a model before generating completion.');
        }

        $jsonData = array_merge([
            'model' => $this->modelName,
            'prompt' => $prompt,
            'options' => $modelOptions,
        ], $this->modelOptions);

        if (!empty($modelOptions)) {
            $jsonData['options'] = $modelOptions;
        }

        return $this->callEndpoint('generate', $jsonData);
    }

    public function generateChatCompletion(array $messages = [], array $modelOptions = []): ChatCompletionResponse
    {
        $jsonData = array_merge([
            'model' => $this->modelName,
            'messages' => $messages,
            'options' => $modelOptions,
        ], $this->modelOptions);

        return $this->callEndpoint('chat', $jsonData);
    }

    public function generateEmbeddings(string $model, $prompt, array $options = []): OllamaResponse
    {
        return $this->callEndpoint('embeddings', ['model' => $model, 'prompt' => $prompt, 'options' => $options, 'stream' => false]);
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function useModel(string $modelName)
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function setParameters(array $modelOptions)
    {
        $this->modelOptions = $modelOptions;
    }

    private function callEndpoint(string $endpoint, array $data): OllamaResponse
    {
        try {
            $response = $this->httpClient->request('POST', $endpoint, ['json' => $data]);
        } catch (\Exception $e) {
            throw new \Evoware\OllamaPHP\Exceptions\RequestException($e->getMessage(), $e->getCode(), $e);
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
}
