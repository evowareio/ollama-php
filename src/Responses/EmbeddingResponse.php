<?php

namespace Evoware\OllamaPHP\Responses;

class EmbeddingResponse extends OllamaResponse
{
    public function getEmbedding(): array
    {
        return $this->data['embedding'] ?? [];
    }
}
