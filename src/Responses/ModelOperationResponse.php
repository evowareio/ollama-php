<?php

namespace Evoware\OllamaPHP\Responses;

class ModelOperationResponse extends OllamaResponse
{
    public function getMessage(): string
    {
        return $this->jsonData['message']['content'] ?? '';
    }

    public function getMessageRole(): string
    {
        return $this->jsonData['message']['role'] ?? '';
    }

    public function getTotalDuration(): int
    {
        return $this->jsonData['total_duration'] ?? 0;
    }

    public function getLoadDuration(): int
    {
        return $this->jsonData['load_duration'] ?? 0;
    }

    public function getPromptEvalCount(): int
    {
        return $this->jsonData['prompt_eval_count'] ?? 0;
    }

    public function getPromptEvalDuration(): int
    {
        return $this->jsonData['prompt_eval_duration'] ?? 0;
    }

    public function getEvalCount(): int
    {
        return $this->jsonData['eval_count'] ?? 0;
    }

    public function getEvalDuration(): int
    {
        return $this->jsonData['eval_duration'] ?? 0;
    }
}
