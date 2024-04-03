<?php

namespace Evoware\OllamaPHP\Repositories;

use GuzzleHttp\Client;
use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\DataObjects\Model;

class ModelRepository
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create($name, ?ModelFile $modelFile = null, bool $stream = false)
    {
        $response = $this->makeRequest('POST', 'models', [
            'name' => $name,
            'stream' => $stream,
        ]);

        return $response->getStatusCode() === 201;
    }

    /**
     * @return Model[]
     */
    public function list(): array
    {
        $response = $this->makeRequest('GET', 'tags');

        return array_map(fn ($item) => new Model($item), json_decode($response->getBody()->getContents(), true));
    }

    public function info(string $modelName): Model
    {
        $response = $this->makeRequest('GET', "show", [
            'name' => $modelName,
        ]);

        return new Model(json_decode($response->getBody()->getContents(), true));
    }

    public function copy($source, $destination): bool
    {
        $response = $this->makeRequest('POST', 'copy', [
            'source' => $source,
            'destination' => $destination,
        ]);

        return $response->getStatusCode() === 200 && json_decode($response->getBody()->getContents(), true)['success'] === true;
    }

    public function delete($modelName): bool
    {
        $response = $this->makeRequest('POST', "delete", [
            'name' => $modelName,
        ]);

        return $response->getStatusCode() === 204 && json_decode($response->getBody()->getContents(), true)['success'] === true;
    }

    public function pull($modelName, $stream = false): bool
    {
        $response = $this->makeRequest('POST', "pull", [
            'name' => $modelName,
            'stream' => $stream,
        ]);

        return $response->getStatusCode() === 200 && json_decode($response->getBody()->getContents(), true)['success'] === true;
    }

    public function push($modelName, $stream = false): bool
    {
        $response = $this->makeRequest('POST', "push", [
            'name' => $modelName,
            'stream' => $stream,
        ]);

        return $response->getStatusCode() === 200;
    }

    protected function makeRequest(string $method, string $uri, array $data = [])
    {

        $response = $this->client->request($method, $uri, [
            'json' => $data
        ]);

        return $response;
    }
}
