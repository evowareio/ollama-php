<?php

namespace Evoware\OllamaPHP\Responses;

class EmbeddingResponse extends OllamaResponse
{
    public function getResponse(): array
    {
        return $this->data['embedding'] ?? [];
    }

    public function __toString(): string
    {
        return json_encode($this->getResponse());
    }
}
