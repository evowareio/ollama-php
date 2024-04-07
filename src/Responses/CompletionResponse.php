<?php

namespace Evoware\OllamaPHP\Responses;

class CompletionResponse extends InferenceResponse
{
    public function getResponse()
    {
        return $this->getData('response');
    }
}
