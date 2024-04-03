<?php

namespace Evoware\OllamaPHP\Exceptions;

class RequestException extends \Evoware\OllamaPHP\Exceptions\OllamaException
{
    public function __construct(string $message = 'Model not found', int $code = 404, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
