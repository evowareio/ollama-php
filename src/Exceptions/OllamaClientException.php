<?php

namespace Evoware\OllamaPHP\Exceptions;

class OllamaClientException extends OllamaException
{
    public function __construct(string $message = 'Malformed request data.', int $code = 400, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
