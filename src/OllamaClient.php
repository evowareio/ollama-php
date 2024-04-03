<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\ClientInterface;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Repositories\ModelRepository;

class OllamaClient
{
    private ClientInterface $httpClient;
    private ModelRepository $modelRepository;
    private string $modelName = '';
    private array $modelOptions = [];

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the model modelRepository.
     * The model modelRepository is used to create and manage models.
     *
     * @return ModelRepository
     */
    public function getModelRepository(): ModelRepository
    {
        if (!isset($this->modelRepository)) {
            $this->modelRepository = new ModelRepository($this->httpClient);
        }

        return $this->modelRepository;
    }

    /**
     * Generate a completion using the provided prompt, model name, and model modelOptions.
     *
     * @param string $prompt The prompt for generating the completion.
     * @param string|null $modelName (Optional) The name of the model to use for completion.
     * @param array $modelOptions (Optional) Additional modelOptions for generating the completion.
     * @return CompletionResponse
     */
    public function generateCompletion(string $prompt, ?string $modelName = null, array $modelOptions = []): CompletionResponse
    {
        if (!empty($modelName)) {
            // Override model if provided on runtime
            $this->modelName = $modelName;
        } elseif (isset($modelOptions['model'])) {
            // Use model name specified in modelOptions
            $this->modelName = $modelOptions['model'];
        }

        $jsonData = array_merge([
            'model' => $this->modelName,
            'prompt' => $prompt,
            'modelOptions' => $modelOptions,
        ], $this->modelOptions);

        if (!empty($modelOptions)) {
            $jsonData['modelOptions'] = $modelOptions;
        }

        return $this->callEndpoint('generate', $jsonData);
    }

    /**
     * Generate a chat completion response.
     *
     * @param array $messages list of chat messages
     * @param array $modelOptions modelOptions for the model
     * @return ChatCompletionResponse
     */
    public function generateChatCompletion(array $messages = [], ?string $modelName = null, array $modelOptions = []): ChatCompletionResponse
    {
        $jsonData = array_merge([
            'model' => $this->modelName,
            'messages' => $messages,
            'options' => $modelOptions,
        ], $this->modelOptions);

        return $this->callEndpoint('chat', $jsonData);
    }


    /**
     * Generate embeddings for a given prompt using the specified model or options.
     *
     * @param string $prompt The prompt for which to generate embeddings.
     * @param string|null $modelName The name of the model to use.
     * @param array $modelOptions Additional options for the model.
     * @return EmbeddingResponse The response containing the generated embeddings.
     */
    public function generateEmbeddings(string|array $prompt, ?string $modelName = null, array $modelOptions = []): EmbeddingResponse
    {
        if (!empty($this->modelName)) {
            // Override model if provided on runtime
            $this->modelName = $modelName;
        } elseif (isset($modelOptions['model'])) {
            // Use model name specified in modelOptions
            $this->modelName = $modelOptions['model'];
        }

        if (is_array($prompt)) {
            $prompt = implode("\n", $prompt);
        }

        return $this->callEndpoint('embeddings', ['model' => $this->modelName, 'prompt' => $prompt, 'modelOptions' => $modelOptions, 'stream' => false]);
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

    public function model(): ModelRepository
    {
        return $this->getModelRepository();
    }
}
