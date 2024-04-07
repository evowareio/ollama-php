<?php

namespace Evoware\OllamaPHP\Responses;

class ChatCompletionResponse extends InferenceResponse
{
    public function getMessage()
    {
        return $this->getData('message');
    }
}
