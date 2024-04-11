<?php

namespace Evoware\OllamaPHP;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Client;
use Evoware\OllamaPHP\Traits\ValidatesFields;
use Evoware\OllamaPHP\Traits\MakesHttpRequests;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Repositories\ModelRepository;
use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\Exceptions\OllamaClientException;

/**
 * OllamaClient is the main class for interacting with the Ollama API.
 */
class OllamaClient
{
    use MakesHttpRequests;
    use ValidatesFields;

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
        if (!isset($this->modelRepository)) {
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
     * @throws OllamaClientException
     */
    public function generateCompletion(string $prompt, ?string $modelName = null, array $modelOptions = []): CompletionResponse
    {
        $this->validate(['model'], array_merge(['model' => $modelName ?? $this->modelName], $modelOptions));

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
     * @param  string|null  $modelName  (Optional) The name of the model to use for completion.
     * @param  array|null  $modelOptions  modelOptions for the model
     * @throws OllamaClientException
     */
    public function generateChatCompletion(array $messages = [], ?string $modelName = null, array $modelOptions = []): ChatCompletionResponse
    {
        $this->validate(['model'], array_merge(['model' => $modelName ?? $this->modelName], $modelOptions));

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
     * @param  array|null $modelOptions  Additional clientOptions for the model.
     * @return EmbeddingResponse The response containing the generated embeddings.
     * @throws OllamaClientException
     */
    public function generateEmbeddings(string|array $prompt, ?string $modelName = null, array $modelOptions = []): EmbeddingResponse
    {
        $this->validate(['model', 'prompt'], array_merge(['model' => $modelName, 'prompt' => $prompt], $modelOptions));

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
    public function setModel(?string $modelName): self
    {
        $this->modelName = $modelName ?? $this->modelName;

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
        if (!empty($modelName)) {
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
