<?php

namespace Evoware\OllamaPHP\Responses;

use Psr\Http\Message\ResponseInterface;

interface OllamaResponseInterface
{
    public function getResponse(): mixed;
    public function getHttpResponse(): ResponseInterface;
    public function isDone(): bool;
    public function getStatusCode(): int;
}
