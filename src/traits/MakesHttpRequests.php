<?php

namespace Evoware\OllamaPHP\Traits;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\ClientInterface;
use Evoware\OllamaPHP\Responses\OllamaResponseInterface;
use Evoware\OllamaPHP\Responses\OllamaResponse;
use Evoware\OllamaPHP\Responses\EmbeddingResponse;
use Evoware\OllamaPHP\Responses\CompletionResponse;
use Evoware\OllamaPHP\Responses\ChatCompletionResponse;
use Evoware\OllamaPHP\Exceptions\OllamaServerException;
use Evoware\OllamaPHP\Exceptions\OllamaException;
use Evoware\OllamaPHP\Exceptions\OllamaClientException;

trait MakesHttpRequests
{
    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    protected function request(string $method, string $endpoint, array $data = []): OllamaResponseInterface
    {
        try {
            $data['stream'] = false;
            $response = $this->client->request($method, $endpoint, [
                'json' => $data,
            ]);
        } catch (ClientException $e) {
            throw new OllamaClientException($e->getMessage(), $e->getCode(), $e);
        } catch (ServerException $e) {
            throw new OllamaServerException($e->getMessage(), $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new OllamaException($e->getMessage(), $e->getCode(), $e);
        }

        switch ($endpoint) {
            case 'generate':
                return new CompletionResponse($response);
            case 'chat':
                return new ChatCompletionResponse($response);
            case 'embeddings':
                return new EmbeddingResponse($response);
            default:
                return new OllamaResponse($response);
        }
    }

    public function get($endpoint, $data = []): OllamaResponseInterface
    {
        return $this->request('GET', $endpoint, $data);
    }

    public function post($endpoint, $data = []): OllamaResponseInterface
    {
        return $this->request('POST', $endpoint, $data);
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
