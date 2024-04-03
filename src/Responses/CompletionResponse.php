<?php

namespace Evoware\OllamaPHP\Responses;

class CompletionResponse extends OllamaResponse
{
    public function getModel(): ?string
    {
        return $this->data['model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getResponse(): mixed
    {
        return $this->data['response'] ?? null;
    }

    public function getContext(): ?array
    {
        return $this->data['context'] ?? null;
    }

    public function getTotalDuration(): ?int
    {
        return $this->data['total_duration'] ?? null;
    }

    public function getLoadDuration(): ?int
    {
        return $this->data['load_duration'] ?? null;
    }

    public function getPromptEvalCount(): ?int
    {
        return $this->data['prompt_eval_count'] ?? null;
    }

    public function getPromptEvalDuration(): ?int
    {
        return $this->data['prompt_eval_duration'] ?? null;
    }

    public function getEvalCount(): ?int
    {
        return $this->data['eval_count'] ?? null;
    }

    public function getEvalDuration(): ?int
    {
        return $this->data['eval_duration'] ?? null;
    }

    public function __toString(): string
    {
        return $this->getResponse();
    }
}
