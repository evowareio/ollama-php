<?php

namespace Evoware\OllamaPHP\Responses;

class ChatCompletionResponse extends CompletionResponse
{
    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->data['message'] ?? null;
    }
}
