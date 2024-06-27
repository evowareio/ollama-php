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
    protected ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    protected function request(string $method, string $endpoint, array $options = []): OllamaResponseInterface
    {
        try {
            $response = $this->httpClient->request($method, $endpoint, [
                'json' => $options,
            ]);
            if (isset($options['stream']) && $options['stream'] === true) {
                return $response;
            }
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

    public function get($endpoint, $options = []): OllamaResponseInterface
    {
        return $this->request('GET', $endpoint, $options);
    }

    public function post($endpoint, $options = []): OllamaResponseInterface
    {
        return $this->request('POST', $endpoint, $options);
    }

    public function getClient(): ClientInterface
    {
        return $this->httpClient;
    }

    protected function stream($method, $uri, array $options = [], callable $callback)
    {
        $response = $this->httpClient->request($method, $uri, $options);
        $body = $response->getBody();

        while (!$body->eof()) {
            $chunk = $body->read(1024);
            if ($chunk) {
                $data = json_decode($chunk, true);
                $callback($data);
            }
        }
    }
}
