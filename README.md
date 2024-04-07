# Ollama PHP

A comprehensive PHP library designed for seamless interaction with the Ollama server, facilitating a range of operations from generating text completions to managing models and producing embeddings.

**But what's Ollama?**
Ollama is a tool for running open-source Large Language Models locally. You can find more information about Ollama directly at the project [repository](https://github.com/ollama/ollama) or [documentation page](https://github.com/ollama/ollama/blob/main/docs/README.md).

## Installation

To integrate the Ollama PHP Adapter into your project, use Composer for a smooth installation process:

```bash
composer require evoware/ollama-php
```

## Usage:

The Ollama PHP Adapter simplifies the complexity of interacting with the Ollama server, providing intuitive methods for various functionalities.

### Generating Text Completions
Generate text completions by providing a prompt to the model. Access the completion text using the getResponse() method:
```php
use GuzzleHttp\Client as HttpClient;
use Evoware\OllamaPHP\OllamaClient;

$ollamaClient = new OllamaClient(new HttpClient());

$response = $ollamaClient->generateCompletion('The capital of France is ', ['model' => 'mistral:7b']);
$completionText = $response->getResponse(); // Returns the generated completion text.

```

#### Interacting with models:
Manage your local models by listing, pulling, and interacting through the provided methods:
```php
// List all local models
$models = $ollamaClient->model()->list();

// Pull a local model
$result = $ollamaClient->model()->pull('mistral:7b');

// Alternatively, access via the getModelRepository method
$result = $ollamaClient->getModelRepository()->pull('mistral:7b');

// Load a Modelfile
$ollamaClient->fromModelFile('/path/to/modelfile');
```

To learn more about model file format, please visit Ollama Model File documentation [here](https://github.com/ollama/ollama/blob/main/docs/modelfile.md).

#### Generating embeddings:
Produce embeddings for a given text, returning an array of embedding data through the `EmbeddingsResponse` object:
```php
$embeddings = $ollamaClient->generateEmbeddings('This is my text to be embedded.', 'nomic-embed-text');
```

### Response Types
The adapter delineates responses into specific object types for clarity and ease of use:

* **CompletionResponse**: Handles the data from text completion requests.
* **ChatCompletionResponse**: Manages chat completion data.
* **EmbeddingsResponse**: Encapsulates embedding data.
* **ModelOperationResponse**: Represents the outcome of model operations (create, delete, pull, etc.).

Responses provide access to the underlying Guzzle Response via the `getHttpResponse()` method for further customization and handling.

## Roadmap

* Handling streaming responses.
* Introduction of ChatSession class for storing chat context in a styructured manner.
* Laravel facade support

## Caution
⚠️ Development Stage: This project is in its early development stages and is not recommended for production environments. It is provided as-is, without any guarantees. Proceed with caution and use at your own risk. ⚠️


