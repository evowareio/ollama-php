<?php

namespace Evoware\OllamaPHP;

use Evoware\OllamaPHP\OllamaClient;

class Inference {
    private $client;

    public function __construct(OllamaClient $client)
    {
        $this->client = $client;
    }

    public function generateCompletion($prompt, $options): string
    {
        return 'Hello World.'; // TODO: remove placeholder
    }

    public function generateChatCompletion($messages, $options): string
    {
        // Placeholder for generating a chat completion
    }
}
