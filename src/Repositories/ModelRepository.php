<?php

namespace Evoware\OllamaPHP\Repositories;

use GuzzleHttp\ClientInterface;
use Evoware\OllamaPHP\Traits\ValidatesFields;
use Evoware\OllamaPHP\Traits\MakesHttpRequests;
use Evoware\OllamaPHP\Models\ModelFile;
use Evoware\OllamaPHP\DataObjects\Model;

class ModelRepository
{
    use MakesHttpRequests;
    use ValidatesFields;

    protected ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function fromModelFile(string $modelName, ModelFile $modelfile)
    {
        return $this->create($modelName, $modelfile, true);
    }

    public function create($name, ?ModelFile $modelFile = null)
    {
        $this->validate(['name'], ['name' => $name]);
        $response = $this->post('models', [
            'name' => $name,
            'modelfile' => isset($modelfile) ? (string) $modelFile : null,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return $response->getStatusCode() === 201;
    }

    /**
     * @return Model[]
     */
    public function list(): array
    {
        $response = $this->get('tags', [
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return array_map(fn ($item) => new Model($item), json_decode($response->getData(), true));
    }

    /**
     * Retrieves information about a specific model.
     *
     * @param  string  $modelName  The name of the model.
     * @return Model The model object containing the retrieved information.
     */
    public function info(string $modelName): Model
    {
        $this->validate(['name'], ['name' => $modelName]);
        $response = $this->get('show', [
            'name' => $modelName,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return new Model(json_decode($response->getData(), true));
    }

    /**
     * Copy a model from the source to the destination.
     *
     * @param  string  $source  The source file path.
     * @param  string  $destination  The destination file path.
     * @return bool Returns true if the file was successfully copied, false otherwise.
     */
    public function copy($source, $destination): bool
    {
        $response = $this->post('copy', [
            'source' => $source,
            'destination' => $destination,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return $response->isSuccessful();
    }

    /**
     * Deletes a model.
     *
     * @param  string  $modelName  The name of the model to delete.
     * @return bool Returns true if the model was deleted successfully, false otherwise.
     */
    public function delete($modelName): bool
    {
        $response = $this->post('delete', [
            'name' => $modelName,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return $response->isSuccessful();
    }

    /**
     * Pulls a model from the library.
     *
     * @param  string  $modelName  The name of the model to pull.
     * @return bool Returns true if the model was pulled successfully, false otherwise.
     */
    public function pull($modelName): bool
    {
        $response = $this->post('pull', [
            'name' => $modelName,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return $response->isSuccessful();
    }

    /**
     * Pushes a model to the library.
     *
     * @param  string  $modelName  The name of the model to push.
     * @return bool Returns true if the push was successful, false otherwise.
     */
    public function push($modelName): bool
    {
        $response = $this->post('push', [
            'name' => $modelName,
            'stream' => false, // TODO introduce parameter once streaming is supported
        ]);

        return $response->isSuccessful();
    }
}
