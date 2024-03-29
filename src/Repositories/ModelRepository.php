<?php

namespace Evoware\OllamaPHP\Repositories;

use Evoware\OllamaPHP\OllamaClient;

class ModelRepository
{
    private $client;

    public function __construct(OllamaClient $client) {
        $this->client = $client;
    }

    public function createModel($modelData) {
        return $this->post('models', $modelData);
    }

    public function listLocalModels() {
        return $this->get('models');
    }

    public function showModelInformation($modelName) {
        return $this->get("models/$modelName");
    }

    public function copyModel($modelName, $newModelName) {
        return $this->post("models/$modelName/copy", ['name' => $newModelName]);
    }

    public function deleteModel($modelName) {
        return $this->delete("models/$modelName");
    }

    public function pullModel($modelName) {
        return $this->post("models/$modelName/pull");
    }

    public function pushModel($modelName) {
        return $this->post("models/$modelName/push");
    }
}
