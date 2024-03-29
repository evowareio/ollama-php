<?php

namespace Evoware\OllamaPHP\DataObjects;

class Prompt {
    private $ollama;
    private $prompt;
    private $options = [];

    public function __construct($ollama) {
        $this->ollama = $ollama;
    }

    public function set($prompt) {
        $this->prompt = $prompt;
        return $this; // Return $this for method chaining
    }

    public function options(array $options) {
        $this->options = $options;
        return $this; // Return $this for method chaining
    }

    public function inference() {
        // Implementation
        return $response; // Assuming $response is obtained after making an HTTP request with $this->prompt and $this->options
    }
}
