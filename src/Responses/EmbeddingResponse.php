<?php

namespace Evoware\OllamaPHP\Responses;

class EmbeddingResponse extends OllamaResponse
{
    public function getEmbedding(): array
    {
        return $this->getData('embedding');
    }

    public function __toString(): string
    {
        return json_encode($this->getData('embedding'));
    }
}
