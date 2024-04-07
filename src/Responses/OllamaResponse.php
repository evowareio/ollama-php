<?php

namespace Evoware\OllamaPHP\Responses;

use Psr\Http\Message\ResponseInterface;

class OllamaResponse implements OllamaResponseInterface
{
    protected ResponseInterface $guzzleResponse;

    protected array $data = [];

    public function __construct(ResponseInterface $response)
    {
        $contents = $response->getBody()->getContents();
        $this->guzzleResponse = $response;

        if (is_array($contents)) {
            $this->data = $contents;
        } else {
            $this->data = json_decode($contents, true);
        }
    }

    /**
     * Retrieves the data stored in the OllamaResponse object.
     *
     * @param string|null $key The key of the data to retrieve. If null, returns all the data.
     * @return mixed The retrieved data. If the key is not found, returns null.
     */
    public function getData(?string $key = null): mixed
    {
        return $key ? $this->data[$key] ?? null : $this->data;
    }

    public function getModelName(): ?string
    {
        return $this->data['model'] ?? null;
    }

    public function getCreatedAt(): ?string
    {
        return $this->data['created_at'] ?? null;
    }

    public function getHttpResponse(): ResponseInterface
    {
        return $this->guzzleResponse;
    }

    public function isDone(): bool
    {
        return (bool) $this->data['done'] ?? false;
    }

    public function getStatusCode(): int
    {
        return $this->guzzleResponse->getStatusCode();
    }

    public function isSuccessful(): bool
    {
        return $this->getStatusCode() >= 200 && $this->getStatusCode() < 300;
    }
}
