<?php

namespace Evoware\OllamaPHP\Responses;

class EmbeddingResponse extends OllamaResponse
{
    public function getResponse(): array
    {
        return $this->data['embedding'] ?? [];
    }
}
