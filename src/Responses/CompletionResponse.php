<?php

namespace Evoware\OllamaPHP\Responses;

class CompletionResponse extends OllamaResponse
{
    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->data['model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getResponse(): ?string
    {
        return $this->data['response'] ?? null;
    }

    /**
     * @return array|null
     */
    public function getContext(): ?array
    {
        return $this->data['context'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getTotalDuration(): ?int
    {
        return $this->data['total_duration'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getLoadDuration(): ?int
    {
        return $this->data['load_duration'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getPromptEvalCount(): ?int
    {
        return $this->data['prompt_eval_count'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getPromptEvalDuration(): ?int
    {
        return $this->data['prompt_eval_duration'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getEvalCount(): ?int
    {
        return $this->data['eval_count'] ?? null;
    }

    /**
     * @return int|null
     */
    public function getEvalDuration(): ?int
    {
        return $this->data['eval_duration'] ?? null;
    }
}
