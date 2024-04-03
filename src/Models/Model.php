<?php

namespace Evoware\OllamaPHP\DataObjects;

use DateTime;

/* Example response from the /api/tags endpoint
{
    "models": [
        {
            "name": "openhermes:latest",
            "model": "openhermes:latest",
            "modified_at": "2024-03-21T11:14:04.027678993+01:00",
            "size": 4108928574,
            "digest": "95477a2659b7539758230498d6ea9f6bfa5aa51ffb3dea9f37c91cacbac459c1",
            "details": {
                "parent_model": "",
                "format": "gguf",
                "family": "llama",
                "families": [
                    "llama"
                ],
                "parameter_size": "7B",
                "quantization_level": "Q4_0"
            }
        }
    ]
} */

class Model
{
    /** @var string Model name */
    public string $name;

    /** @var string the model ID */
    public string $id;

    /** @var int Size in megabytes */
    public int $size;

    /** @var string Model version */
    public string $version;

    /** @var string Model digest */
    public string $digest;

    /** @var string Model version */
    public array $details;

    public DateTime $modifiedAt;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->id = $data['model'];
        $this->size = $data['size'];
        $this->digest = $data['digest'];
        $this->modifiedAt = DateTime::createFromFormat(DateTime::ATOM, $data['modified_at']);
        $this->details = $data['details'];
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
