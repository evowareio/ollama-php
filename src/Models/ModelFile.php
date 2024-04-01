<?php

namespace Evoware\OllamaPHP\Models;

use Evoware\OllamaPHP\Exceptions\OllamaException;

class ModelFile
{
    public string $path;

    private array $parameters = [
        'mirostat',
        'mirostatEta',
        'mirostatTau',
        'numCtx',
        'numGqa',
        'numGpu',
        'numThread',
        'repeatLastN',
        'repeatPenalty',
        'temperature',
        'seed',
        'numPredict',
        'topK',
        'topP',
    ];

    public function __construct(array $data = [])
    {
        foreach ($this->parameters as $key) {
            $this->$key = $data[$key] ?? null;
        }
    }

    /**
     * Creates a new instance of ModelFile from a given modelfile.xml file. \
     * Possible instructions in modelfile: 
     * FROM - Defines the base model to use.
     * PARAMETER - Sets the parameters for how Ollama will run the model. 
     * TEMPLATE - The full prompt template to be sent to the model.
     * SYSTEM - Specifies the system message that will be set in the template.
     * ADAPTER - Defines the (Q)LoRA adapters to apply to the model.
     * LICENSE - Specifies the legal license.
     * MESSAGE - Specify message history.
     * @param string $path Absolute path to modelfile.xml
     * @return ModelFile
     */
    public static function fromFile(string $path): ModelFile
    {
        if (!file_exists($path)) {
            throw new OllamaException("File not found: '$path'");
        }
        $xml = @simplexml_load_file($path);
        
        if ($xml === false) {
            throw new OllamaException("Failed to parse modelfile XML: '$path'");
        }

        $data = [
            'from' => (string) $xml->FROM,
            'template' => (string) $xml->TEMPLATE,
            'system' => (string) $xml->SYSTEM,
            'adapters' => (string) $xml->ADAPTER,
            'license' => (string) $xml->LICENSE,
            'message' => (string) $xml->MESSAGE,
        ];

        foreach ($xml->PARAMETER as $parameter) {
            $name = (string) $parameter['name'];
            $value = (string) $parameter;
            $data[$name] = $value;
        }

        return new self($data);
    }

    /**
     * Convert ModelFile to array
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert ModelFile to JSON
     * @param int $options JSON encoding options
     * @return string
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
