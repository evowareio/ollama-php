<?php

namespace Evoware\OllamaPHP\Responses;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;
use DateTime;

class OllamaResponse
{
    protected string $json;
    protected Response $httpResponse;
    protected string $model;
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

    public function __construct(ResponseInterface $response)
    {
        $this->httpResponse = $response;
        $this->json = $response->getBody()->getContents();

        $data = json_decode($this->json, true, 512, JSON_THROW_ON_ERROR);

        // TODO: add validation for all possible fields of the response

        $this->model = $data['model'];
        $this->createdAt = DateTime::createFromFormat(DateTime::ATOM, $data['created_at']);
        $this->response = $data['response'];
        $this->done = $data['done'];
        $this->context = $data['context'];
        $this->totalDuration = $data['total_duration'];
        $this->loadDuration = $data['load_duration'];
        $this->promptEvalCount = $data['prompt_eval_count'];
        $this->promptEvalDuration = $data['prompt_eval_duration'];
        $this->evalCount = $data['eval_count'];
        $this->evalDuration = $data['eval_duration'];
    }

    public function getHttpResponse(): Response
    {
        return $this->httpResponse;
    }
}
