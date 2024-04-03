# Ollama PHP

⚠️ UNDER CONSTRUCTION ⚠️

A PHP library for interacting with local Ollama server.

## Installation
```bash
composer require evoware/ollama-php
```

## Example usage:
```php
$ollamaClient = new OllamaClient('http://localhost:11434');

// Get the list of available models
$models = $ollamaClient->models()->list();

// Generate a completion using a specific model and prompt
$completion = $ollamaClient->generateCompletion('model_name', 'Hello, world!', ['max_tokens' => 50]);

// Generate a chat completion using a specific model and messages
$chatCompletion = $ollamaClient->generateChatCompletion('model_name', [
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => 'Who won the world cup in 2022?']
]);

// Generate embeddings for a given prompt
$embeddings = $ollamaClient->generateEmbeddings('model_name', 'Hello, world!', ['method' => 'pq']);
```

## Warning

⚠️ This project is currently under construction and not ready for use ⚠️

