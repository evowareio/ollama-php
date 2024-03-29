<?php

namespace Evoware\OllamaPHP\Responses;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateTime;
use Psr\Http\Message\ResponseInterface;

class OllamaResponse {
// Example response body from Ollama API request:
// {
//     "model": "llama2",
//     "created_at": "2023-08-04T19:22:45.499127Z",
//     "response": "The sky is blue because it is the color of the sky.",
//     "done": true,
//     "context": [1, 2, 3],
//     "total_duration": 4935886791,
//     "load_duration": 534986708,
//     "prompt_eval_count": 26,
//     "prompt_eval_duration": 107345000,
//     "eval_count": 237,
//     "eval_duration": 4289432000
//  }

    protected $model;
    protected DateTime $createdAt;
    protected string $response;
    protected bool $done;
    protected array $context;
    protected int $totalDuration;
    protected int $loadDuration;
    protected int $promptEvalCount;
    protected int $promptEvalDuration;
    protected int $evalCount;
    protected int $evalDuration;

    private string $data;

    public function __construct(array $responseData) {
        $this->data = $responseData;
    }

    public function parse() {
        $data = json_decode($this->data, true);

        $this->model = $data['model'];
        $this->createdAt = Carbon::createFromFormat(Carbon::ATOM, $data['created_at']);
        $this->response = $data['response'];
        $this->done = $data['done'];
        $this->context = $data['context'];
        $this->totalDuration = CarbonInterval::microseconds($data['total_duration'])->totalMilliseconds;
        $this->loadDuration = CarbonInterval::microseconds($data['load_duration'])->totalMilliseconds;
        $this->promptEvalCount = $data['prompt_eval_count'];
        $this->promptEvalDuration = CarbonInterval::microseconds($data['prompt_eval_duration'])->totalMilliseconds;
        $this->evalCount = $data['eval_count'];
        $this->evalDuration = CarbonInterval::microseconds($data['eval_duration'])->totalMilliseconds;
    }
}
