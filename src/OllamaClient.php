<?php

namespace Evoware\OllamaPHP;

use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Traits\MakesHttpRequests;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * OllamaClient is the main class for interacting with the Ollama API.
 */
class OllamaClient
{
    use MakesHttpRequests;

    private ModelRepository $modelRepository;

    private string $modelName = '';

    private ?ModelFile $modelfile = null;

    public function __construct(?ClientInterface $httpClient = null, array $clientOptions = [])
    {
        $this->client = $httpClient ?? new Client($clientOptions);
    }

    /**
     * Get the model modelRepository.
     * The model modelRepository is used to create and manage models.
     */
    public function getModelRepository(): ModelRepository
    {
        if (! isset($this->modelRepository)) {
            $this->modelRepository = new ModelRepository($this->client);
        }

        return $this->modelRepository;
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
            'clientOptions' => $modelOptions,
        ], $this->getModelOptions());

        return $this->post('generate', $jsonData);
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
            'clientOptions' => $modelOptions,
        ], $this->getModelOptions());

        return $this->post('chat', $jsonData);
    }

    /**
     * Generate embeddings for a given prompt using the specified model or clientOptions.
     *
     * @param  string  $prompt  The prompt for which to generate embeddings.
     * @param  string|null  $modelName  The name of the model to use.
     * @param  array  $modelOptions  Additional clientOptions for the model.
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

        return $this->post('embeddings', ['model' => $this->modelName, 'prompt' => $prompt, 'modelOptions' => $modelOptions]);
    }

    /**
     * Returns the HTTP client used by the OllamaClient.
     *
     * @return ClientInterface The HTTP client.
     */
    public function getHttpClient(): ClientInterface
    {
        return $this->getClient();
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
     * Get the model clientOptions.
     * Returns an array of model clientOptions based on the current model file.
     */
    private function getModelOptions(): array
    {
        return $this->modelfile ? $this->modelfile->toArray() : [];
    }
}
