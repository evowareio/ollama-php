<?php

namespace Evoware\OllamaPHP\Responses;

class ChatCompletionResponse extends CompletionResponse
{
    /**
     * @return string|null
     */
    public function getResponse(): array
    {
        return $this->data['message'] ?? [];
    }
}
