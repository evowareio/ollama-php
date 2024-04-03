<?php

namespace Evoware\OllamaPHP\Responses;

use Psr\Http\Message\ResponseInterface;

class OllamaResponse
{
    protected ResponseInterface $guzzleResponse;
    protected array $data;

    public function __construct(ResponseInterface $response)
    {
        $this->guzzleResponse = $response;
        $this->data = json_decode($this->guzzleResponse->getBody()->getContents(), true);
    }

    public function getResponse(): ?string
    {
        return $this->data['response'] ?? null;
    }

    public function getModelName(): ?string
    {
        return $this->data['model'] ?? null;
    }

    public function getCreatedAt(): ?string
    {
        return $this->data['created_at'] ?? null;
    }

    public function getHttpResponse(): ?ResponseInterface
    {
        return $this->guzzleResponse;
    }

    public function isDone(): ?bool
    {
        return $this->data['done'] ?? false;
    }
}
