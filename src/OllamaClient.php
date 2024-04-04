<?php

namespace Evoware\OllamaPHP;

use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use GuzzleHttp\ClientInterface;

/**
 * Represents a client for interacting with the Ollama API.
 *
 * This class provides methods for making requests to the Ollama API and
 * handling the responses.
 */
class OllamaClient
{
    private ClientInterface $httpClient;

    private ModelRepository $modelRepository;

    private string $modelName = '';

    private ?ModelFile $modelfile = null;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the model modelRepository.
     * The model modelRepository is used to create and manage models.
     */
    public function getModelRepository(): ModelRepository
    {
        if (! isset($this->modelRepository)) {
            $this->modelRepository = new ModelRepository($this->httpClient);
        }

        return $this->modelRepository;
    }

    public function fromModelFile(ModelFile $modelfile)
    {
        $this->modelfile = $modelfile;

        return $this;
    }

    /**
     * Generate a completion using the provided prompt, model name, and model modelOptions.
     *
     * @param  string  $prompt  The prompt for generating the completion.
     * @param  string|null  $modelName  (Optional) The name of the model to use for completion.
     * @param  array  $modelOptions  (Optional) Additional modelOptions for generating the completion.
     */
    public function generateCompletion(string $prompt, ?string $modelName = null, array $modelOptions = []): CompletionResponse
    {
        if (! empty($modelName)) {
            $this->modelName = $modelName;
        } elseif (isset($modelOptions['model'])) {
            $this->modelName = $modelOptions['model'];
        }

        $jsonData = array_merge([
            'model' => $this->modelName,
            'prompt' => $prompt,
            'options' => $modelOptions,
        ], $this->getModelOptions());

        return $this->callEndpoint('generate', $jsonData);
    }

    /**
     * Generate a chat completion response.
     *
     * @param  array  $messages  list of chat messages
     * @param  array  $modelOptions  modelOptions for the model
     */
    public function generateChatCompletion(array $messages = [], ?string $modelName = null, array $modelOptions = []): ChatCompletionResponse
    {
        $jsonData = array_merge([
            'model' => $this->modelName,
            'messages' => $messages,
            'options' => $modelOptions,
        ], $this->getModelOptions());

        return $this->callEndpoint('chat', $jsonData);
    }

    /**
     * Generate embeddings for a given prompt using the specified model or options.
     *
     * @param  string  $prompt  The prompt for which to generate embeddings.
     * @param  string|null  $modelName  The name of the model to use.
     * @param  array  $modelOptions  Additional options for the model.
     * @return EmbeddingResponse The response containing the generated embeddings.
     */
    public function generateEmbeddings(string|array $prompt, ?string $modelName = null, array $modelOptions = []): EmbeddingResponse
    {
        if (! empty($this->modelName)) {
            // Override model if provided on runtime
            $this->modelName = $modelName;
        } elseif (isset($modelOptions['model'])) {
            // Use model name specified in modelOptions
            $this->modelName = $modelOptions['model'];
        }

        if (is_array($prompt)) {
            $prompt = implode("\n", $prompt);
        }

        return $this->callEndpoint('embeddings', ['model' => $this->modelName, 'prompt' => $prompt, 'modelOptions' => $modelOptions]);
    }

    /**
     * Returns the HTTP client used by the OllamaClient.
     *
     * @return ClientInterface The HTTP client.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Sets the model to be used for inference.
     *
     * @param  string  $modelName  The name of the model.
     * @return $this
     */
    public function setModel(string $modelName): self
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * Get the name of the model.
     */
    public function getModel(): string
    {
        return $this->modelName;
    }

    /**
     * Call the specified endpoint with the provided data.
     *
     * @param  string  $endpoint  The endpoint to call.
     * @param  array  $data  The data to send to the endpoint.
     * @return OllamaResponse The response from the endpoint.
     */
    private function callEndpoint(string $endpoint, array $data): OllamaResponse
    {
        try {
            $data['stream'] = false;
            $response = $this->httpClient->request('POST', $endpoint, ['json' => json_encode($data)]);
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

    /**
     * Get the model modelRepository.
     * The model modelRepository is used to create and manage models.
     */
    public function model(?string $modelName = null): ModelRepository
    {
        if (! empty($modelName)) {
            $this->modelName = $modelName;
        }

        return $this->getModelRepository();
    }

    /**
     * Get the model options.
     * Returns an array of model options based on the current model file.
     */
    private function getModelOptions(): array
    {
        return $this->modelfile ? $this->modelfile->toArray() : [];
    }
}
