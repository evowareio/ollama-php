<?php

namespace Evoware\OllamaPHP\Repositories;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Client;
use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\Exceptions\OllamaException;
use Evoware\OllamaPHP\DataObjects\Model;

class ModelRepository
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create($name, ModelFile $modelFile = null, bool $stream = false)
    {
        return $this->makeRequest('POST', 'models', [
            'name' => $name,
            'modelfile' => $modelFile->path,
            'stream' => $stream,
            'path' => $modelFile->path,
        ]);
    }

    /** 
     * @return Model[] - the list of models
     */
    public function list(): array
    {
        $response = $this->makeRequest('GET', 'tags');

        return array_map(fn ($item) => new Model($item), json_decode($response->getBody()->getContents(), true));
    }

    public function info(string $modelName): Model
    {
        
    }

    public function copy($source, $destination): bool
    {
        return $this->makeRequest('POST', 'copy', [
            'source' => $source,
            'destination' => $destination,
        ]);
    }

    public function delete($modelName): bool
    {
        return $this->makeRequest('POST', "delete", [
            'name' => $modelName,
        ]);
    }

    public function pull($modelName, $stream = false): bool
    {
        return $this->makeRequest('POST', "pull", [
            'name' => $modelName,
            'stream' => $stream,
        ]);
    }

    public function push($modelName, $stream = false): bool
    {
        return $this->makeRequest('POST', "push", [
            'name' => $modelName,
            'stream' => $stream,
        ]);
    }

    protected function makeRequest(string $method, string $uri, array $data = [])
    {

        $response = $this->client->request($method, $uri, [
            'json' => $data
        ]);

        return $response;
    }

    protected function makeAsyncRequest(string $method, string $uri, array $data = []): PromiseInterface
    {
        /** @var \GuzzleHttp\Promise\PromiseInterface */
        $response = $this->client->request($method, $uri, [
            'future' => true,
            'json' => $data
        ]);

        $response
            ->then(
                function (Response $response) {
                    echo 'Success: ' . $response->getStatusCode() . PHP_EOL;
                    return $response;
                },
                function (\Throwable $error) {
                    echo 'Exception: ' . $error->getMessage() . PHP_EOL;
                    throw $error;
                }
            )
            ->then(
                function (Response $response) {
                    echo $response->getReasonPhrase() . PHP_EOL;
                },
                function (\Throwable $error) {
                    echo 'Error: ' . $error->getMessage() . PHP_EOL;
                    throw $error;
                }
            );

        return $response;
    }

<<<<<<<<<<<<<<  ✨ Codeium Command ⭐ >>>>>>>>>>>>>>>>

    public function testExceptions()
    {
        $this->expectException(OllamaException::class);

        $this->client = new Client(['base_uri' => 'http://localhost:8000']);

        $this->makeRequest('GET', '/models/non-existing-model');
    }

<<<<<<<  6d4813ca-21d4-45c0-884f-517b007f5353  >>>>>>>
}
