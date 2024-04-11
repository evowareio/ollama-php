<?php

namespace Evoware\OllamaPHP\Exceptions;

class OllamaServerException extends OllamaException
{
    public function __construct(string $message = 'Request to Ollama API failed.', int $code = 400, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
